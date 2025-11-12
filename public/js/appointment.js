document.addEventListener('DOMContentLoaded', () => {
    const serviceSelect = document.getElementById('service_id');
    const departmentSelect = document.getElementById('department_id');
    const doctorSelect = document.getElementById('doctor_id');
    const examSelect = document.getElementById('medical_examination');
    const apptTimeInput = document.getElementById('appointment_time');
    const form = document.getElementById('appointmentForm');
    const dateInput = document.getElementById('appointment_date');
    const sdEmpty = document.getElementById('sd_empty');
    const sdContent = document.getElementById('sd_content');
    const sdName = document.getElementById('sd_name');
    const sdPrice = document.getElementById('sd_price');
    const sdDept = document.getElementById('sd_dept');
    const sdDesc = document.getElementById('sd_desc');

    // 🔹 Giới hạn ngày đặt lịch: cho phép cả Thứ 7 và Chủ nhật, trong 7 ngày kể từ hôm nay
    const today = new Date();
    const start = new Date(today);
    const end = new Date(today);
    end.setDate(end.getDate() + 6); // 7 ngày bao gồm cả cuối tuần
    dateInput.min = start.toISOString().split('T')[0];
    dateInput.max = end.toISOString().split('T')[0];

    function updateServiceDetails() {
        const opt = serviceSelect.options[serviceSelect.selectedIndex];
        if (!opt || !opt.value) {
            if (sdEmpty && sdContent) { sdEmpty.classList.remove('d-none'); sdContent.classList.add('d-none'); }
            return;
        }
        const deptId = opt.dataset.departmentId;
        if (deptId) {
            departmentSelect.value = deptId;
            doctorSelect.querySelectorAll('option').forEach(o => {
                o.style.display = (o.dataset.departmentId === deptId || o.value === '') ? '' : 'none';
            });
            doctorSelect.value = '';
        }
        const nameText = opt.textContent ? opt.textContent.split('(')[0].trim() : '';
        const price = opt.dataset.price ? Number(opt.dataset.price) : null;
        const desc = opt.dataset.description || '';
        if (sdName) sdName.textContent = nameText;
        if (sdPrice) sdPrice.textContent = price !== null && !Number.isNaN(price) ? price.toLocaleString('vi-VN') + ' đ' : '';
        const deptOpt = departmentSelect.options[departmentSelect.selectedIndex];
        if (sdDept) sdDept.textContent = deptOpt ? deptOpt.textContent : '';
        if (sdDesc) sdDesc.textContent = desc;
        if (sdEmpty && sdContent) { sdEmpty.classList.add('d-none'); sdContent.classList.remove('d-none'); }
    }

    // 🔹 Khi chọn dịch vụ → tự gán khoa và lọc bác sĩ
    serviceSelect.addEventListener('change', updateServiceDetails);

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

    updateServiceDetails();
});