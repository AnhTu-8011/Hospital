document.addEventListener('DOMContentLoaded', () => {
    const serviceSelect = document.getElementById('service_id');
    const departmentSelect = document.getElementById('department_id');
    const doctorSelect = document.getElementById('doctor_id');
    const examSelect = document.getElementById('medical_examination');
    const apptTimeInput = document.getElementById('appointment_time');
    const form = document.getElementById('appointmentForm');
    const dateInput = document.getElementById('appointment_date');

    // 🔹 Giới hạn ngày đặt lịch: cho phép cả Thứ 7 và Chủ nhật, trong 7 ngày kể từ hôm nay
    const today = new Date();
    const start = new Date(today);
    const end = new Date(today);
    end.setDate(end.getDate() + 6); // 7 ngày bao gồm cả cuối tuần
    dateInput.min = start.toISOString().split('T')[0];
    dateInput.max = end.toISOString().split('T')[0];

    // 🔹 Khi chọn dịch vụ → tự gán khoa và lọc bác sĩ
    serviceSelect.addEventListener('change', () => {
        const opt = serviceSelect.options[serviceSelect.selectedIndex];
        const deptId = opt.dataset.departmentId;
        if (deptId) {
            departmentSelect.value = deptId;
            doctorSelect.querySelectorAll('option').forEach(o => {
                o.style.display = (o.dataset.departmentId === deptId || o.value === '') ? '' : 'none';
            });
            doctorSelect.value = '';
        }
    });

    // 🔹 Khi chọn ca khám → gán appointment_time
    examSelect.addEventListener('change', () => {
        apptTimeInput.value = examSelect.value;
    });

    // 🔹 Validate form
    form.addEventListener('submit', e => {
        const required = form.querySelectorAll('[required]');
        let valid = true;
        required.forEach(f => {
            if (!f.value.trim()) {
                valid = false;
                f.classList.add('is-invalid');
            } else f.classList.remove('is-invalid');
        });
        if (!valid) {
            e.preventDefault();
            alert('Vui lòng nhập đầy đủ thông tin bắt buộc.');
        }
    });
});