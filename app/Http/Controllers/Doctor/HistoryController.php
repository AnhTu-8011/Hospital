<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Appointment, Patient, MedicalRecord};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HistoryController extends Controller
{
        /**
     * Lịch sử khám: danh sách hồ sơ bệnh án
     */
    public function history(Request $request)
    {
        $doctorId = optional($request->user()->doctor)->id;

        $medicalRecords = MedicalRecord::with(['appointment.patient', 'appointment.service'])
            ->when($doctorId, function ($query) use ($doctorId) {
                $query->whereHas('appointment', function ($q) use ($doctorId) {
                    $q->where('doctor_id', $doctorId);
                });
            }, function ($query) {
                // If no doctor found for current user, do not return any records
                $query->whereRaw('1 = 0');
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('doctor.patient_history', compact('medicalRecords'));
    }
}
