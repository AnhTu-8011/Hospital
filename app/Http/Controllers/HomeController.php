<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;   // nếu cần cho modal
use App\Models\Service;  // nếu cần cho modal
use App\Models\ServiceSymptom;
use App\Models\Disease;
use App\Models\DiseaseSymptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    // Trang welcome (hoặc home)
    public function welcome()
    {
        $departments = Department::all();
        // nếu modal đặt lịch cần bác sĩ và dịch vụ:
        $doctors = Doctor::with(['user', 'department'])->get();
        // Lấy toàn bộ dịch vụ (sẽ lọc theo khoa ở view)
        $services = Service::with('department')->get();

        return view('welcome', compact('departments', 'doctors', 'services'));
    }

    // Trang tư vấn theo triệu chứng (frontend)
    public function advisorPage(Request $request)
    {
        $symptomQuery = (string) $request->input('symptom');
        $selectedDepartmentId = null; // không lọc theo khoa ở trang tư vấn
        $suggestedServices = collect();
        $suggestedDepartments = collect();
        $suggestedDoctors = collect();
        $suggestedDiseases = collect();
        $departments = Department::all();

        if ($symptomQuery) {
            // Chuẩn hóa từ khóa: cắt khoảng trắng, lower, bỏ trùng, bỏ từ quá ngắn
            $keywords = collect(explode(',', $symptomQuery))
                ->map(fn($s) => trim($s))
                ->filter(fn($s) => $s !== '')
                ->map(fn($s) => mb_strtolower($s))
                ->unique()
                ->filter(fn($s) => mb_strlen($s) >= 2)
                ->values();

            $serviceIds = collect();
            $diseaseIds = collect();

            foreach ($keywords as $keyword) {
                $matchedServiceIds = ServiceSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->pluck('service_id');
                $serviceIds = $serviceIds->merge($matchedServiceIds);

                $matchedDiseaseIds = DiseaseSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->pluck('disease_id');
                $diseaseIds = $diseaseIds->merge($matchedDiseaseIds);
            }

            $serviceIds = $serviceIds->unique();
            $diseaseIds = $diseaseIds->unique();

            // Gợi ý dịch vụ: tính điểm theo số triệu chứng khớp, chỉ giữ dịch vụ có ít nhất 1 triệu chứng khớp, sau đó sắp xếp
            if ($serviceIds->isNotEmpty()) {
                $suggestedServices = Service::with(['department', 'symptoms'])
                    ->whereIn('id', $serviceIds)
                    ->get()
                    ->map(function ($service) use ($keywords) {
                        $matched = [];
                        foreach ($service->symptoms as $symptom) {
                            foreach ($keywords as $kw) {
                                if (stripos($symptom->symptom_name, $kw) !== false) {
                                    $matched[] = $symptom->symptom_name;
                                    break;
                                }
                            }
                        }
                        $matched = array_values(array_unique($matched));
                        $service->matched_symptoms_count = count($matched);
                        return $service;
                    })
                    // Đảm bảo chỉ giữ lại các dịch vụ thực sự có triệu chứng khớp từ khóa
                    ->filter(function ($service) {
                        return ($service->matched_symptoms_count ?? 0) > 0;
                    })
                    ->sortByDesc('matched_symptoms_count')
                    ->take(6)
                    ->values();
            }

            // Gợi ý bệnh: tính điểm theo số triệu chứng khớp, chỉ giữ bệnh có ít nhất 1 triệu chứng khớp, sau đó sắp xếp, giới hạn
            if ($diseaseIds->isNotEmpty()) {
                $suggestedDiseases = Disease::with(['department', 'symptoms'])
                    ->whereIn('id', $diseaseIds)
                    ->get()
                    ->map(function ($disease) use ($keywords) {
                        $matched = [];
                        foreach ($disease->symptoms as $symptom) {
                            foreach ($keywords as $kw) {
                                if (stripos($symptom->symptom_name, $kw) !== false) {
                                    $matched[] = $symptom->symptom_name;
                                    break;
                                }
                            }
                        }
                        $matched = array_values(array_unique($matched));
                        $disease->matched_symptoms_count = count($matched);
                        return $disease;
                    })
                    // Đảm bảo chỉ giữ lại các bệnh thực sự có triệu chứng khớp từ khóa
                    ->filter(function ($disease) {
                        return ($disease->matched_symptoms_count ?? 0) > 0;
                    })
                    ->sortByDesc('matched_symptoms_count')
                    ->take(6)
                    ->values();
            }

            // Khoa gợi ý: ưu tiên khoa từ BỆNH nếu có bệnh; nếu không có bệnh thì dùng khoa từ DỊCH VỤ
            if ($suggestedDiseases->isNotEmpty()) {
                $departmentIds = $suggestedDiseases->pluck('department_id')
                    ->filter()
                    ->unique()
                    ->values();
            } else {
                $departmentIds = $suggestedServices->pluck('department_id')
                    ->filter()
                    ->unique()
                    ->values();
            }
            if ($departmentIds->isNotEmpty()) {
                $suggestedDepartments = Department::whereIn('id', $departmentIds)
                    ->take(4)
                    ->get();
                $suggestedDoctors = Doctor::with(['user', 'department'])
                    ->whereIn('department_id', $departmentIds)
                    ->take(8)
                    ->get();

                // Chỉ hiển thị dịch vụ thuộc các khoa đã gợi ý
                if ($suggestedServices->isNotEmpty()) {
                    $suggestedServices = $suggestedServices
                        ->filter(fn($s) => $departmentIds->contains($s->department_id))
                        ->sortByDesc('matched_symptoms_count')
                        ->take(6)
                        ->values();
                }
            }
        }

        return view('home.advisor.index', compact(
            'symptomQuery',
            'selectedDepartmentId',
            'departments',
            'suggestedDiseases',
            'suggestedDepartments',
            'suggestedDoctors',
            'suggestedServices'
        ));
    }

    // Trang danh sách bác sĩ (frontend)
    public function doctorsPage(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $doctors = Doctor::with(['user', 'department'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%");
                });
            })
            ->paginate(8)
            ->withQueryString();
        return view('home.doctors.index', compact('doctors', 'q'));
    }

    // Trang danh sách chuyên khoa (frontend)
    public function departmentsPage(Request $request)
    {
        $query = $request->input('symptom');
        $departments = Department::query();

        if (Schema::hasColumn('departments', 'is_active')) {
            $departments->where('is_active', true);
        }
        
        // Tìm kiếm triệu chứng trong mô tả của các khoa
        if ($query) {
            // Kiểm tra xem FULLTEXT index có tồn tại không
            if ($this->hasFulltextIndex('departments', 'description')) {
                // Sử dụng FULLTEXT SEARCH với NATURAL LANGUAGE MODE
                // Điều này cho phép tìm kiếm tự nhiên và nhanh hơn LIKE
                $departments->whereRaw(
                    "MATCH(description) AGAINST(? IN NATURAL LANGUAGE MODE)",
                    [$query]
                );
            } else {
                // Fallback về LIKE nếu FULLTEXT index chưa được tạo
                $departments->where('description', 'LIKE', '%' . $query . '%');
            }
        }
        
        $departments = $departments->paginate(6)->withQueryString();
        $services = Service::with('department')->get();
        
        return view('home.departments.index', compact('departments', 'services', 'query'));
    }

    /**
     * Kiểm tra xem FULLTEXT index có tồn tại cho cột cụ thể không
     */
    private function hasFulltextIndex($table, $column)
    {
        try {
            $indexes = DB::select(
                "SHOW INDEX FROM {$table} WHERE Column_name = ? AND Index_type = 'FULLTEXT'",
                [$column]
            );
            return count($indexes) > 0;
        } catch (\Exception $e) {
            return false;
        }
    }

    // Trang danh sách dịch vụ (frontend)
    public function servicesPage(Request $request)
    {
        $symptomQuery = $request->input('symptom');
        $selectedDepartmentId = $request->input('department_id');
        $suggestedServices = collect();
        $suggestedDepartments = collect();
        $suggestedDoctors = collect();
        $suggestedDiseases = collect();
        $departments = Department::all();
        $symptomSuggestions = collect();

        // Tìm kiếm dịch vụ và bệnh dựa trên triệu chứng
        if ($symptomQuery) {
            // Tách từ khóa tìm kiếm thành mảng (hỗ trợ tìm nhiều triệu chứng)
            $keywords = array_filter(array_map('trim', explode(',', $symptomQuery)));
            
            // Tìm các dịch vụ có triệu chứng khớp (không phân biệt hoa thường)
            $serviceIds = collect();
            $diseaseIds = collect();
            
            foreach ($keywords as $keyword) {
                $matchedIds = ServiceSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->pluck('service_id');
                $serviceIds = $serviceIds->merge($matchedIds);

                $matchedDiseaseIds = DiseaseSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->pluck('disease_id');
                $diseaseIds = $diseaseIds->merge($matchedDiseaseIds);
            }
            
            $serviceIds = $serviceIds->unique();
            $diseaseIds = $diseaseIds->unique();

            if ($serviceIds->isNotEmpty()) {
                // Lấy các dịch vụ phù hợp cùng với khoa
                $suggestedServices = Service::with(['department', 'symptoms'])
                    ->whereIn('id', $serviceIds)
                    ->when($selectedDepartmentId, function ($q) use ($selectedDepartmentId) {
                        $q->where('department_id', $selectedDepartmentId);
                    })
                    ->get()
                    ->map(function ($service) use ($keywords) {
                        // Đếm số lượng triệu chứng khớp với từ khóa tìm kiếm
                        $matchedSymptomsCount = $service->symptoms->filter(function ($symptom) use ($keywords) {
                            foreach ($keywords as $keyword) {
                                if (stripos($symptom->symptom_name, $keyword) !== false) {
                                    return true;
                                }
                            }
                            return false;
                        })->count();
                        
                        // Thêm thuộc tính để sắp xếp
                        $service->matched_symptoms_count = $matchedSymptomsCount;
                        return $service;
                    })
                    ->sortByDesc('matched_symptoms_count')
                    ->values();

                // Lấy các khoa liên quan
                $departmentIds = $suggestedServices->pluck('department_id')->unique();
                if ($departmentIds->isNotEmpty()) {
                    $suggestedDepartments = Department::whereIn('id', $departmentIds)->get();

                    // Lấy các bác sĩ thuộc các khoa này
                    $suggestedDoctors = Doctor::with(['user', 'department'])
                        ->whereIn('department_id', $departmentIds)
                        ->get();
                }
            }

            if ($diseaseIds->isNotEmpty()) {
                // Lấy các bệnh phù hợp và tính điểm khớp triệu chứng
                $suggestedDiseases = Disease::with(['department', 'symptoms'])
                    ->whereIn('id', $diseaseIds)
                    ->when($selectedDepartmentId, function ($q) use ($selectedDepartmentId) {
                        $q->where('department_id', $selectedDepartmentId);
                    })
                    ->get()
                    ->map(function ($disease) use ($keywords) {
                        $matchedSymptomsCount = $disease->symptoms->filter(function ($symptom) use ($keywords) {
                            foreach ($keywords as $keyword) {
                                if (stripos($symptom->symptom_name, $keyword) !== false) {
                                    return true;
                                }
                            }
                            return false;
                        })->count();
                        $disease->matched_symptoms_count = $matchedSymptomsCount;
                        return $disease;
                    })
                    ->sortByDesc('matched_symptoms_count')
                    ->values();
            }
        }

        // Gợi ý triệu chứng theo khoa khi người dùng đã chọn khoa
        if ($selectedDepartmentId) {
            $serviceIdsByDept = Service::where('department_id', $selectedDepartmentId)->pluck('id');
            if ($serviceIdsByDept->isNotEmpty()) {
                $symptomSuggestions = ServiceSymptom::whereIn('service_id', $serviceIdsByDept)
                    ->select('symptom_name')
                    ->get()
                    ->groupBy(function ($item) {
                        return mb_strtolower(trim($item->symptom_name));
                    })
                    ->map(function ($group) {
                        return [
                            'name' => $group->first()->symptom_name,
                            'count' => $group->count(),
                        ];
                    })
                    ->sortByDesc('count')
                    ->values()
                    ->take(12);
            }
        }

        // Lấy tất cả dịch vụ (hoặc chỉ hiển thị gợi ý nếu có tìm kiếm)
        if ($symptomQuery && $suggestedServices->isNotEmpty()) {
            $services = $suggestedServices;
        } else {
            $services = Service::with(['department', 'symptoms'])
                ->when($selectedDepartmentId, function ($q) use ($selectedDepartmentId) {
                    $q->where('department_id', $selectedDepartmentId);
                })
                ->paginate(6)
                ->withQueryString();
        }

        return view('home.services.index', compact(
            'services', 
            'symptomQuery', 
            'suggestedServices', 
            'suggestedDepartments', 
            'suggestedDoctors',
            'suggestedDiseases',
            'departments',
            'selectedDepartmentId',
            'symptomSuggestions'
        ));
    }
}
