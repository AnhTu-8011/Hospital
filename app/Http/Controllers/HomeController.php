<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Doctor;   // nếu cần cho modal
use App\Models\Service;  // nếu cần cho modal
use App\Models\ServiceSymptom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $departments = Department::all();
        $symptomSuggestions = collect();

        // Tìm kiếm dịch vụ dựa trên triệu chứng
        if ($symptomQuery) {
            // Tách từ khóa tìm kiếm thành mảng (hỗ trợ tìm nhiều triệu chứng)
            $keywords = array_filter(array_map('trim', explode(',', $symptomQuery)));
            
            // Tìm các dịch vụ có triệu chứng khớp (không phân biệt hoa thường)
            $serviceIds = collect();
            
            foreach ($keywords as $keyword) {
                $matchedIds = ServiceSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%' . strtolower($keyword) . '%'])
                    ->pluck('service_id');
                $serviceIds = $serviceIds->merge($matchedIds);
            }
            
            $serviceIds = $serviceIds->unique();

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
            'departments',
            'selectedDepartmentId',
            'symptomSuggestions'
        ));
    }
}
