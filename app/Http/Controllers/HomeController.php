<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Disease;
use App\Models\DiseaseSymptom;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\ServiceSymptom;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    /**
     * Hiển thị trang chủ với danh sách khoa, bác sĩ và dịch vụ.
     *
     * @return \Illuminate\View\View
     */
    public function welcome()
    {
        $departments = Department::all();
        $doctors = Doctor::with(['user', 'department'])->get();
        $services = Service::with('department')->get();

        return view('welcome', compact('departments', 'doctors', 'services'));
    }

    /**
     * Trang tư vấn theo triệu chứng (không lọc theo khoa).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function advisorPage(Request $request)
    {
        // 1. Lấy triệu chứng từ request
        $symptomQuery = (string) $request->input('symptom');

        // 2. Lấy danh sách tất cả khoa
        $departments = Department::all();
        
        // 3. Gọi method xử lý tìm kiếm (KHÔNG lọc theo khoa)

        $suggestions = $this->getSuggestionsBySymptoms($symptomQuery, null);

        // 4. Trả về view với dữ liệu
            return view('home.advisor.index', array_merge([
            'symptomQuery' => $symptomQuery,
            'selectedDepartmentId' => null,
            'departments' => $departments,
        ], $suggestions));
    }

    /**
     * Trang danh sách bác sĩ với tìm kiếm.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function doctorsPage(Request $request)
    {
        $q = trim((string) $request->input('q'));

        $doctors = Doctor::with(['user', 'department'])
            ->when($q !== '', function ($query) use ($q) {
                $query->whereHas('user', function ($subQuery) use ($q) {
                    $subQuery->where('name', 'like', "%{$q}%");
                });
            })
            ->paginate(8);
        /** @var \Illuminate\Pagination\LengthAwarePaginator $doctors */
        $doctors = $doctors->withQueryString();

        return view('home.doctors.index', compact('doctors', 'q'));
    }

    /**
     * Trang danh sách chuyên khoa với tìm kiếm triệu chứng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function departmentsPage(Request $request)
    {
        $query = $request->input('symptom');
        $departments = Department::query();

        // Lọc chỉ lấy khoa đang hoạt động nếu có cột is_active
        if (Schema::hasColumn('departments', 'is_active')) {
            $departments->where('is_active', true);
        }

        // Tìm kiếm theo triệu chứng nếu có
        if ($query) {
            if ($this->hasFulltextIndex('departments', 'description')) {
                $departments->whereRaw(
                    'MATCH(description) AGAINST(? IN NATURAL LANGUAGE MODE)',
                    [$query]
                );
            } else {
                $departments->where('description', 'LIKE', '%'.$query.'%');
            }
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $departments */
        $departments = $departments->paginate(6)->withQueryString();
        $services = Service::with('department')->get();

        return view('home.departments.index', compact('departments', 'services', 'query'));
    }

    /**
     * Trang danh sách dịch vụ với tìm kiếm và lọc theo khoa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function servicesPage(Request $request)
    {
        // 1. Lấy triệu chứng và khoa từ request
        $symptomQuery = $request->input('symptom');
        $selectedDepartmentId = $request->input('department_id');
        $departments = Department::all();

        // 2. Gọi method xử lý tìm kiếm (CÓ lọc theo khoa)
        $suggestions = $this->getSuggestionsBySymptoms($symptomQuery, $selectedDepartmentId);

        // 3. Lấy gợi ý triệu chứng theo khoa
        $symptomSuggestions = $this->getSymptomSuggestionsByDepartment($selectedDepartmentId);

        // 4. Nếu có triệu chứng và có kết quả → dùng kết quả gợi ý
        if ($symptomQuery && $suggestions['suggestedServices']->isNotEmpty()) {
            /** @var \Illuminate\Support\Collection $services */
            $services = $suggestions['suggestedServices'];
        } else {
            $services = Service::with(['department', 'symptoms'])
                ->when($selectedDepartmentId, function ($query) use ($selectedDepartmentId) {
                    $query->where('department_id', $selectedDepartmentId);
                })
                ->paginate(6);
            /** @var \Illuminate\Pagination\LengthAwarePaginator $services */
            $services = $services->withQueryString();
        }

        // 5. Trả về view với dữ liệu
        return view('home.services.index', array_merge([
            'services' => $services,
            'symptomQuery' => $symptomQuery,
            'departments' => $departments,
            'selectedDepartmentId' => $selectedDepartmentId,
            'symptomSuggestions' => $symptomSuggestions,
        ], $suggestions));
    }

    /**
     * Lấy các gợi ý (dịch vụ, bệnh, khoa, bác sĩ) dựa trên triệu chứng.
     *
     * @param  string|null  $symptomQuery
     * @param  int|null  $selectedDepartmentId
     * @return array
     */
    private function getSuggestionsBySymptoms(?string $symptomQuery, ?int $selectedDepartmentId): array
    {
        // 1. Khởi tạo các collections rỗng để lưu kết quả
        $suggestedServices = collect();
        $suggestedDepartments = collect();
        $suggestedDoctors = collect();
        $suggestedDiseases = collect();

        // 2. Kiểm tra nếu không có triệu chứng → trả về rỗng
        if (empty($symptomQuery)) {
            return [
                'suggestedServices' => $suggestedServices,
                'suggestedDepartments' => $suggestedDepartments,
                'suggestedDoctors' => $suggestedDoctors,
                'suggestedDiseases' => $suggestedDiseases,
            ];
        }

        // 3.Chuẩn hóa từ khóa và tìm các ID khớp
        $keywords = $this->normalizeKeywords($symptomQuery);

        // 4. Tìm các ID dịch vụ và bệnh khớp
        $serviceIds = $this->findMatchingServiceIds($keywords);
        $diseaseIds = $this->findMatchingDiseaseIds($keywords);
        
        // 5. Lấy danh sách dịch vụ gợi ý
        if ($serviceIds->isNotEmpty()) {
            $suggestedServices = $this->getSuggestedServices($serviceIds, $keywords, $selectedDepartmentId);
            $departmentIds = $suggestedServices->pluck('department_id')->unique();

            // 6. Kiểm tra nếu có khoa → lấy danh sách khoa
                if ($departmentIds->isNotEmpty()) {
                $suggestedDepartments = Department::whereIn('id', $departmentIds)->get();
                $suggestedDoctors = Doctor::with(['user', 'department'])
                    ->whereIn('department_id', $departmentIds)
                    ->get();
            }
        }

        // 7. Lấy danh sách bệnh gợi ý
        if ($diseaseIds->isNotEmpty()) {
            $suggestedDiseases = $this->getSuggestedDiseases($diseaseIds, $keywords, $selectedDepartmentId);
        }

        // 8. Ưu tiên khoa từ bệnh, nếu không có thì dùng khoa từ dịch vụ
        if ($suggestedDiseases->isNotEmpty()) {
            $departmentIds = $suggestedDiseases->pluck('department_id')->filter()->unique()->values();
        } elseif ($suggestedServices->isNotEmpty()) {
            $departmentIds = $suggestedServices->pluck('department_id')->filter()->unique()->values();
        } else {
            $departmentIds = collect();
        }

        // 9. Lấy lại danh sách khoa và bác sĩ theo department IDs
        if ($departmentIds->isNotEmpty()) {
            $suggestedDepartments = Department::whereIn('id', $departmentIds)->take(4)->get();
            $suggestedDoctors = Doctor::with(['user', 'department'])
                ->whereIn('department_id', $departmentIds)
                ->take(8)
                ->get();

            // Lọc lại dịch vụ theo khoa đã gợi ý
            if ($suggestedServices->isNotEmpty()) {
                $suggestedServices = $suggestedServices
                    ->filter(fn($service) => $departmentIds->contains($service->department_id))
                    ->sortByDesc('matched_symptoms_count')
                    ->take(6)
                    ->values();
            }
        }

        return [
            'suggestedServices' => $suggestedServices,
            'suggestedDepartments' => $suggestedDepartments,
            'suggestedDoctors' => $suggestedDoctors,
            'suggestedDiseases' => $suggestedDiseases,
        ];
    }

    /**
     * Chuẩn hóa từ khóa tìm kiếm: loại bỏ khoảng trắng, chuyển về chữ thường, bỏ trùng.
     *
     * @param  string  $symptomQuery
     * @return \Illuminate\Support\Collection
     */
    private function normalizeKeywords(string $symptomQuery): Collection
    {
        return collect(explode(',', $symptomQuery))
            ->map(fn($keyword) => trim($keyword))//loại bỏ khoảng trắng và chuyển về chữ thường không dấu
            ->filter(fn($keyword) => $keyword !== '')//bỏ rỗng
            ->unique()//bỏ trùng
            ->filter(fn($keyword) => mb_strlen($keyword) >= 2)//tối thiểu 2 ký tự
            ->values();//chuyển về mảng
    }

    /**
     * Tìm các ID dịch vụ khớp với từ khóa triệu chứng.
     *
     * @param  \Illuminate\Support\Collection  $keywords
     * @return \Illuminate\Support\Collection
     */
    private function findMatchingServiceIds(Collection $keywords): Collection
    {
        $serviceIds = collect();

        foreach ($keywords as $keyword) {
            // Tìm trong bảng service_symptoms với `LIKE '%keyword%'` (không phân biệt hoa thường)
            $matchedIds = ServiceSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%'.strtolower($keyword).'%'])
                ->pluck('service_id');//lấy service_id
            $serviceIds = $serviceIds->merge($matchedIds);//gộp và loại bỏ trùng
        }

        return $serviceIds->unique();//loại bỏ trùng
    }

    /**
     * Tìm các ID bệnh khớp với từ khóa triệu chứng.
     *
     * @param  \Illuminate\Support\Collection  $keywords
     * @return \Illuminate\Support\Collection
     */
    private function findMatchingDiseaseIds(Collection $keywords): Collection
    {
        $diseaseIds = collect();//khởi tạo collection rỗng

        foreach ($keywords as $keyword) {
            // Tìm trong bảng disease_symptoms với `LIKE '%keyword%'` (không phân biệt hoa thường)
            $matchedIds = DiseaseSymptom::whereRaw('LOWER(symptom_name) LIKE ?', ['%'.strtolower($keyword).'%'])
                ->pluck('disease_id');//lấy disease_id
            $diseaseIds = $diseaseIds->merge($matchedIds);//gộp và loại bỏ trùng
        }

        return $diseaseIds->unique();//loại bỏ trùng
    }

    /**
     * Tính số lượng triệu chứng khớp với từ khóa.
     *
     * @param  \Illuminate\Support\Collection  $symptoms
     * @param  \Illuminate\Support\Collection  $keywords
     * @return int
     */
    private function calculateMatchedSymptomsCount(Collection $symptoms, Collection $keywords): int
    {
        return $symptoms->filter(function ($symptom) use ($keywords) {
            // Kiểm tra nếu triệu chứng khớp với từ khóa
            foreach ($keywords as $keyword) {
                if (stripos($symptom->symptom_name, $keyword) !== false) {
                    return true;//trả về true nếu khớp
                }
            }

            return false;//trả về false nếu không khớp          
        })->count();
    }

    /**
     * Lấy danh sách dịch vụ gợi ý dựa trên triệu chứng.
     *
     * @param  \Illuminate\Support\Collection  $serviceIds
     * @param  \Illuminate\Support\Collection  $keywords
     * @param  int|null  $selectedDepartmentId
     * @return \Illuminate\Support\Collection
     */
    private function getSuggestedServices(Collection $serviceIds, Collection $keywords, ?int $selectedDepartmentId): Collection
    {
        // Lấy danh sách dịch vụ với quan hệ department và symptoms
        return Service::with(['department', 'symptoms'])
            ->whereIn('id', $serviceIds)
            ->when($selectedDepartmentId, function ($query) use ($selectedDepartmentId) {
                $query->where('department_id', $selectedDepartmentId);
            })
            ->get()
            ->map(function ($service) use ($keywords) {
                // Tính số triệu chứng khớp
                $service->matched_symptoms_count = $this->calculateMatchedSymptomsCount(
                    $service->symptoms,
                    $keywords
                );

                return $service;
            })
            ->filter(function ($service) {
                // Chỉ lấy dịch vụ có ít nhất 1 triệu chứng khớp
                return ($service->matched_symptoms_count ?? 0) > 0;//trả về true nếu có triệu chứng khớp
            })
            ->sortByDesc('matched_symptoms_count')//sắp xếp theo độ khớp giảm dần
            ->take(6)//lấy 6 dịch vụ đầu tiên
            ->values();//chuyển về mảng
    }

    /**
     * Lấy danh sách bệnh gợi ý dựa trên triệu chứng.
     *
     * @param  \Illuminate\Support\Collection  $diseaseIds
     * @param  \Illuminate\Support\Collection  $keywords
     * @param  int|null  $selectedDepartmentId
     * @return \Illuminate\Support\Collection
     */
    private function getSuggestedDiseases(Collection $diseaseIds, Collection $keywords, ?int $selectedDepartmentId): Collection
    {
        // Lấy danh sách bệnh với quan hệ department và symptoms
        return Disease::with(['department', 'symptoms'])
            ->whereIn('id', $diseaseIds)
            ->when($selectedDepartmentId, function ($query) use ($selectedDepartmentId) {
                $query->where('department_id', $selectedDepartmentId);
            })
            ->get()
            ->map(function ($disease) use ($keywords) {
                // Tính số triệu chứng khớp
                $disease->matched_symptoms_count = $this->calculateMatchedSymptomsCount(
                    $disease->symptoms,
                    $keywords
                );

                return $disease;
            })
            ->filter(function ($disease) {
                // Chỉ lấy bệnh có ít nhất 1 triệu chứng khớp
                return ($disease->matched_symptoms_count ?? 0) > 0;
            })
            ->sortByDesc('matched_symptoms_count')//sắp xếp theo độ khớp giảm dần
            ->take(6)//lấy 6 bệnh đầu tiên
            ->values();//chuyển về mảng         
    }

    /**
     * Lấy danh sách triệu chứng gợi ý theo khoa.
     *
     * @param  int|null  $departmentId
     * @return \Illuminate\Support\Collection
     */
    private function getSymptomSuggestionsByDepartment(?int $departmentId): Collection
    {
        // Kiểm tra nếu không có khoa → trả về rỗng
        if (!$departmentId) {
            return collect();
        }

        // Lấy danh sách service IDs thuộc khoa
        $serviceIds = Service::where('department_id', $departmentId)->pluck('id');//lấy service_id

        // Kiểm tra nếu không có dịch vụ → trả về rỗng
        if ($serviceIds->isEmpty()) {
            return collect();
        }

        // Lấy triệu chứng từ các dịch vụ và nhóm lại
        return ServiceSymptom::whereIn('service_id', $serviceIds)//tìm trong bảng service_symptoms với service_id
            ->select('symptom_name')
            ->get()//lấy triệu chứng theo service_id
            ->groupBy(function ($item) {
                return mb_strtolower(trim($item->symptom_name));
            })//nhóm triệu chứng theo symptom_name
            ->map(function ($group) {
                return [
                    'name' => $group->first()->symptom_name,//lấy symptom_name đầu tiên
                    'count' => $group->count(),//đếm số lần xuất hiện
                ];
            })//chuyển về mảng
            ->sortByDesc('count')//sắp xếp theo số lần xuất hiện giảm dần
            ->values()//chuyển về mảng
            ->take(12);//lấy 12 triệu chứng đầu tiên
    }

    /**
     * Kiểm tra xem FULLTEXT index có tồn tại cho cột cụ thể không.
     *
     * @param  string  $table
     * @param  string  $column
     * @return bool
     */
    private function hasFulltextIndex(string $table, string $column): bool
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
}
