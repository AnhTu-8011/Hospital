<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LabTest;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    /**
     * Hiển thị form yêu cầu xét nghiệm.
     *
     * @param  int  $recordId
     * @return \Illuminate\View\View
     */
    public function create($recordId)
    {
        $record = MedicalRecord::findOrFail($recordId);
        $departments = Department::all();

        return view('doctor.lab_tests.create', compact('record', 'departments'));
    }

    /**
     * Lưu yêu cầu xét nghiệm mới vào database.
     * - Tạo yêu cầu xét nghiệm cho hồ sơ bệnh án.
     * - Ghi nhận người yêu cầu là bác sĩ hiện tại.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $recordId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $recordId)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'test_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
        ]);

        // Tạo yêu cầu xét nghiệm mới
        LabTest::create([
            'medical_record_id' => $recordId,
            'department_id' => $request->department_id,
            'test_name' => $request->test_name,
            'note' => $request->note,
            'requested_by' => auth()->id(),
        ]);

        // Lấy thông tin hồ sơ bệnh án để redirect
        $record = MedicalRecord::findOrFail($recordId);

        return redirect()->route('doctor.patient.record', $record->appointment_id)
            ->with('success', 'Yêu cầu xét nghiệm đã được tạo.');
    }
}
