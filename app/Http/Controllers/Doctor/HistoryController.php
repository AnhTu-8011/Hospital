<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    /**
     * Hiển thị lịch sử khám: danh sách hồ sơ bệnh án.
     * - Chỉ hiển thị các hồ sơ bệnh án của bác sĩ hiện tại.
     * - Sắp xếp theo thời gian tạo giảm dần (mới nhất trước).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $doctorId = optional(optional($user)->doctor)->id;

        // Lấy danh sách hồ sơ bệnh án của bác sĩ hiện tại
        $medicalRecords = MedicalRecord::with(['appointment.patient', 'appointment.service'])
            ->when($doctorId, function ($query) use ($doctorId) {
                // Lọc theo doctor_id nếu có
                $query->whereHas('appointment', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
            }, function ($query) {
                // Nếu không tìm thấy bác sĩ cho user hiện tại, không trả về bản ghi nào
                $query->whereRaw('1 = 0');
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('doctor.patient_history', compact('medicalRecords'));
    }
}
