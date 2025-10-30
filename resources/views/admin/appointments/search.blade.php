@section('content')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary mb-0">📅 Danh sách lịch hẹn</h4>
        </div>
        <form method="GET" action="{{ route('admin.appointments.index') }}" class="mb-4 flex flex-wrap gap-3 items-end">
            <div>
                <label class="block text-sm font-semibold mb-1">Tên bệnh nhân</label>
                <input type="text" name="patient_name" value="{{ request('patient_name') }}"
                    placeholder="Nhập tên bệnh nhân"
                    class="border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Tên bác sĩ</label>
                <input type="text" name="doctor_name" value="{{ request('doctor_name') }}"
                    placeholder="Nhập tên bác sĩ"
                    class="border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Mã bảo hiểm</label>
                <input type="text" name="insurance_number" value="{{ request('insurance_number') }}"
                    placeholder="Nhập mã bảo hiểm"
                    class="border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Ngày hẹn</label>
                <input type="date" name="appointment_date" value="{{ request('appointment_date') }}"
                    class="border rounded px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Trạng thái</label>
                <select name="status" class="border rounded px-3 py-2">
                    <option value="">-- Tất cả --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Đã Khám</option>
                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Đã Hủy</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600">
                Tìm kiếm
            </button>

            <a href="{{ route('admin.appointments.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500">
                Reset
            </a>
        </form>

