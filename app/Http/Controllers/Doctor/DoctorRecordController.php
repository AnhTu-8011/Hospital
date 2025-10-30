<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Appointment, Patient, MedicalRecord};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DoctorRecordController extends Controller
{
    /**
     * Hiển thị hồ sơ khám bệnh của bệnh nhân
     */
    public function showPatientRecord($appointmentId)
    {
        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])->findOrFail($appointmentId);

        $record = MedicalRecord::firstOrCreate(
            ['appointment_id' => $appointmentId],
            [
                'patient_id' => $appointment->patient_id,
                'description' => null,
                'diagnosis' => null,
                'doctor_conclusion' => null,
                'prescription' => [],
            ]
        );

        return view('doctor.patient_record', compact('appointment', 'record'))
            ->with('patient', $appointment->patient);
    }

    /**
     * Cập nhật thông tin chẩn đoán, kết luận và toa thuốc.
     */
    public function update(Request $request, MedicalRecord $record)
    {
        $request->validate([
            'diagnosis' => 'nullable|string|max:1000',
            'doctor_conclusion' => 'nullable|string|max:1000',
            'prescription' => 'nullable|string|max:2000',
            'image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'status' => 'nullable|in:pending,confirmed,completed',
        ]);

        try {
            // Lưu thông tin
            $record->diagnosis = $request->input('diagnosis');
            $record->doctor_conclusion = $request->input('doctor_conclusion');

            // Xử lý toa thuốc (mỗi dòng là một thuốc)
            $prescriptions = array_filter(preg_split('/\r\n|\r|\n/', $request->input('prescription', '')));
            $record->prescription = $prescriptions; // cast to array

            // Ảnh đơn
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('medical_records', 'public');
                $record->image = $path;
            }

            // Nhiều ảnh
            if ($request->hasFile('images')) {
                $stored = [];
                foreach ($request->file('images') as $file) {
                    if ($file) {
                        $stored[] = $file->store('medical_records', 'public');
                    }
                }
                $current = is_array($record->images) ? $record->images : [];
                $record->images = array_values(array_filter(array_merge($current, $stored)));
            }

            $record->save();

            // Cập nhật trạng thái lịch hẹn nếu có
            if ($request->filled('status')) {
                $status = $request->input('status');
                $allowed = ['pending', 'confirmed', 'completed'];
                if (in_array($status, $allowed, true)) {
                    $appointment = $record->appointment()->first();
                    if ($appointment) {
                        $appointment->status = $status;
                        $appointment->save();
                    }
                }
            }

            return redirect()
                ->back()
                ->with('success', 'Cập nhật hồ sơ bệnh án thành công!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi cập nhật hồ sơ bệnh án', ['error' => $e->getMessage()]);
            return redirect()
                ->back()
                ->with('error', 'Không thể cập nhật hồ sơ bệnh án.');
        }
    }

}
