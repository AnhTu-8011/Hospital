<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Hiển thị danh sách bệnh nhân.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('patients.index');
    }

    /**
     * Hiển thị form tạo bệnh nhân mới.
     *
     * @return void
     */
    public function create()
    {
        // TODO: Implement create method
    }

    /**
     * Lưu bệnh nhân mới vào database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function store(Request $request)
    {
        // TODO: Implement store method
    }

    /**
     * Hiển thị thông tin chi tiết của bệnh nhân.
     *
     * @param  string  $id
     * @return void
     */
    public function show(string $id)
    {
        // TODO: Implement show method
    }

    /**
     * Hiển thị form chỉnh sửa thông tin bệnh nhân.
     *
     * @param  string  $id
     * @return void
     */
    public function edit(string $id)
    {
        // TODO: Implement edit method
    }

    /**
     * Cập nhật thông tin bệnh nhân trong database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return void
     */
    public function update(Request $request, string $id)
    {
        // TODO: Implement update method
    }

    /**
     * Xóa bệnh nhân khỏi database.
     *
     * @param  string  $id
     * @return void
     */
    public function destroy(string $id)
    {
        // TODO: Implement destroy method
    }
}
