<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Department;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    // Yêu cầu xn 
    public function create($recordId)
    {
        $record = MedicalRecord::findOrFail($recordId);
        $departments = Department::all();
        return view('doctor.lab_tests.create', compact('record', 'departments'));
    }

    public function store(Request $request, $recordId)
    {
        $request->validate([
            'test_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        LabTest::create([
            'medical_record_id' => $recordId,
            'department_id' => $request->department_id,
            'test_name' => $request->test_name,
            'note' => $request->note,
            'requested_by' => auth()->id(),
        ]);

        $record = MedicalRecord::findOrFail($recordId);
        return redirect()->route('doctor.patient.record', $record->appointment_id)
            ->with('success', 'Yêu cầu xét nghiệm đã được tạo.');
    }
}
