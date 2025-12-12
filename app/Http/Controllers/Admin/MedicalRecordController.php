<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient.user', 'appointment.doctor.user']);

        if ($request->filled('patient_name')) {
            $query->whereHas('patient.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->patient_name . '%');
            });
        }

        if ($request->filled('doctor_name')) {
            $query->whereHas('appointment.doctor.user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->doctor_name . '%');
            });
        }

        if ($request->filled('appointment_date')) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->whereDate('appointment_date', $request->appointment_date);
            });
        }

        $records = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.medical-records.index', compact('records'));
    }

    // public function create(Request $request)
    // {
    //     $appointment = null;
    //     if ($request->filled('appointment_id')) {
    //         $appointment = Appointment::with(['patient', 'doctor.user'])
    //             ->find($request->appointment_id);
    //     }

    //     return view('admin.medical-records.create', compact('appointment'));
    // }

    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'patient_id' => ['nullable', 'exists:patients,id'],
            'description' => ['nullable', 'string'],
            'diagnosis' => ['nullable', 'string', 'max:1000'],
            'doctor_conclusion' => ['nullable', 'string'],
            'prescription' => ['nullable'],
        ]);

        $appointment = Appointment::with('patient')->findOrFail($data['appointment_id']);

        if (empty($data['patient_id'])) {
            $data['patient_id'] = $appointment->patient_id;
        }

        if (is_string($data['prescription'] ?? null)) {
            $decoded = json_decode($data['prescription'], true);
            $data['prescription'] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        $record = MedicalRecord::create($data);

        return redirect()
            ->route('admin.medical-records.show', $record)
            ->with('success', 'Tạo hồ sơ bệnh án thành công.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->loadMissing([
            'patient.user',
            'appointment.doctor.user',
            'appointment.service',
            'prescriptionItems.medicine',
        ]);

        $items = $medicalRecord->prescriptionItems ?? collect();

        return view('admin.medical-records.show', compact('medicalRecord', 'items'));
    }

    // public function edit(MedicalRecord $medicalRecord)
    // {
    //     $medicalRecord->loadMissing(['patient', 'appointment']);

    //     return view('admin.medical-records.edit', compact('medicalRecord'));
    // }

    // public function update(Request $request, MedicalRecord $medicalRecord)
    // {
    //     $data = $request->validate([
    //         'description' => ['nullable', 'string'],
    //         'diagnosis' => ['nullable', 'string', 'max:1000'],
    //         'doctor_conclusion' => ['nullable', 'string'],
    //         'prescription' => ['nullable'],
    //     ]);

    //     if (is_string($data['prescription'] ?? null)) {
    //         $decoded = json_decode($data['prescription'], true);
    //         $data['prescription'] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    //     }

    //     $medicalRecord->update($data);

    //     return redirect()
    //         ->route('admin.medical-records.show', $medicalRecord)
    //         ->with('success', 'Cập nhật hồ sơ bệnh án thành công.');
    // }
}
