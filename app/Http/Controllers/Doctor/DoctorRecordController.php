<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\PrescriptionItem;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorRecordController extends Controller
{
    /**
     * Hiển thị hồ sơ khám bệnh của bệnh nhân.
     * - Tạo hồ sơ bệnh án mới nếu chưa có.
     * - Hiển thị thông tin lịch hẹn, bệnh nhân và dịch vụ.
     *
     * @param  int  $appointmentId
     * @return \Illuminate\View\View
     */
    public function showPatientRecord($appointmentId)
    {
        // Lấy thông tin lịch hẹn
        $appointment = Appointment::with(['patient', 'doctor.user', 'service'])->findOrFail($appointmentId);

        // Lấy danh sách dịch vụ
        $services = Service::with('department')->orderBy('name')->get();

        // Tạo hoặc lấy hồ sơ bệnh án
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

        return view('doctor.patient_record', compact('appointment', 'record', 'services'))
            ->with('patient', $appointment->patient);
    }

    /**
     * Cập nhật thông tin chẩn đoán, kết luận và toa thuốc.
     * - Lưu thông tin chẩn đoán và kết luận.
     * - Lưu các hạng mục gói dịch vụ đã chọn.
     * - Xử lý toa thuốc chi tiết theo bảng prescription_items.
     * - Xử lý upload ảnh đơn và nhiều ảnh.
     * - Cập nhật trạng thái lịch hẹn nếu có.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicalRecord  $record
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MedicalRecord $record)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'diagnosis' => 'nullable|string|max:1000',
            'doctor_conclusion' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:5120',
            'images.*' => 'nullable|image|max:5120',
            'status' => 'nullable|in:pending,confirmed,completed',
            'service_items' => 'nullable|array',
            'service_items.*' => 'nullable|string',
            'prescription_items' => 'nullable|array',
            'prescription_items.*.medicine_id' => 'nullable|integer|exists:medicines,id',
            'prescription_items.*.dosage' => 'nullable|string|max:255',
            'prescription_items.*.frequency' => 'nullable|string|max:255',
            'prescription_items.*.duration' => 'nullable|string|max:255',
            'prescription_items.*.quantity' => 'nullable|integer|min:0',
            'prescription_items.*.unit' => 'nullable|string|max:255',
            'prescription_items.*.usage' => 'nullable|string',
            'prescription_items.*.note' => 'nullable|string',
        ]);

        try {
            // Lưu thông tin chẩn đoán, kết luận
            $record->diagnosis = $request->input('diagnosis');
            $record->doctor_conclusion = $request->input('doctor_conclusion');

            // Lưu các hạng mục gói dịch vụ mà bác sĩ đã tích chọn
            $items = $request->input('service_items', []);

            if (is_array($items)) {
                $items = array_values(array_filter($items, function ($v) {
                    return is_string($v) && trim($v) !== '';
                }));
            } else {
                $items = [];
            }

            // Lưu vào cột description dạng chuỗi, mỗi dịch vụ 1 dòng
            $record->description = !empty($items)
                ? implode("\n", $items)
                : null;

            // Xử lý toa thuốc chi tiết theo bảng prescription_items
            $items = $request->input('prescription_items', []);

            // Xóa các dòng toa thuốc cũ
            if (method_exists($record, 'prescriptionItems')) {
                $record->prescriptionItems()->delete();
            }

            // Tạo các dòng toa thuốc mới
            if (is_array($items)) {
                foreach ($items as $item) {
                    // Bỏ qua dòng trống (không chọn thuốc và không nhập gì)
                    $hasContent = !empty($item['medicine_id'])
                        || !empty($item['dosage'])
                        || !empty($item['frequency'])
                        || !empty($item['duration'])
                        || !empty($item['quantity'])
                        || !empty($item['unit'])
                        || !empty($item['usage'])
                        || !empty($item['note']);

                    if (!$hasContent) {
                        continue;
                    }

                    PrescriptionItem::create([
                        'prescription_id' => $record->id,
                        'medicine_id' => $item['medicine_id'] ?? null,
                        'dosage' => $item['dosage'] ?? null,
                        'frequency' => $item['frequency'] ?? null,
                        'duration' => $item['duration'] ?? null,
                        'quantity' => $item['quantity'] ?? 0,
                        'unit' => $item['unit'] ?? null,
                        'usage' => $item['usage'] ?? null,
                        'note' => $item['note'] ?? null,
                    ]);
                }
            }

            // Xử lý upload ảnh đơn
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('medical_records', 'public');
                $record->image = $path;
            }

            // Xử lý upload nhiều ảnh
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
