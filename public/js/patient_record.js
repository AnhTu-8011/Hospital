(function initPreview() {
  function setup() {
    const imageInput = document.querySelector('#image');
    const imagesInput = document.querySelector('#images');
    const imagePreview = document.querySelector('#imagePreview');
    const imagesPreview = document.querySelector('#imagesPreview');

    function clear(el){ while (el && el.firstChild) el.removeChild(el.firstChild); }
    function makeThumb(src){ const img = document.createElement('img'); img.src = src; img.className = 'thumb'; return img; }

    if (imageInput && imagePreview) {
      imageInput.addEventListener('change', function() {
        clear(imagePreview);
        const file = this.files && this.files[0];
        if (file) {
          const url = URL.createObjectURL(file);
          imagePreview.appendChild(makeThumb(url));
        }
      });
    }

    if (imagesInput && imagesPreview) {
      imagesInput.addEventListener('change', function() {
        clear(imagesPreview);
        Array.from(this.files || []).forEach(f => {
          const url = URL.createObjectURL(f);
          imagesPreview.appendChild(makeThumb(url));
        });
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setup, { once: true });
  } else {
    setup();
  }
})();

// Auto-fill test name and department from selected test type
document.addEventListener('DOMContentLoaded', function(){
  const sel = document.getElementById('testTypeSelect');
  const nameInput = document.getElementById('testNameInput');
  const deptSel = document.getElementById('departmentSelect');
  if (sel && nameInput && deptSel) {
    sel.addEventListener('change', function(){
      const opt = this.options[this.selectedIndex];
      const n = opt.getAttribute('data-name') || '';
      const d = opt.getAttribute('data-dept') || '';
      if (n) nameInput.value = n;
      if (d) deptSel.value = d;
    });
  }

  // AJAX submit for lab test request form (no page reload)
  const labForm = document.getElementById('labTestForm');
  const alertBox = document.getElementById('labTestAlert');

  if (labForm && alertBox) {
    labForm.addEventListener('submit', function(e){
      e.preventDefault();

      alertBox.style.display = 'none';
      alertBox.className = 'mb-2';
      alertBox.textContent = '';

      const formData = new FormData(labForm);

      fetch(labForm.action, {
        method: 'POST',
        body: formData,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
      })
      .then(function(resp){
        if (!resp.ok) throw new Error('Request failed');
        // Dù server redirect hay trả HTML, chỉ cần thành công là đủ
        return resp.text();
      })
      .then(function(){
        alertBox.className = 'mb-2 alert alert-success';
        alertBox.textContent = 'Gửi yêu cầu xét nghiệm thành công.';
        alertBox.style.display = '';

        // Optional: reset các ô nhập (giữ lại danh sách loại nếu muốn)
        labForm.reset();
      })
      .catch(function(){
        alertBox.className = 'mb-2 alert alert-danger';
        alertBox.textContent = 'Có lỗi xảy ra khi gửi yêu cầu xét nghiệm. Vui lòng thử lại.';
        alertBox.style.display = '';
      });
    });
  }

  // Prescription Management
  initPrescriptionManagement();
});

/**
 * Khởi tạo quản lý toa thuốc (thêm/xóa dòng thuốc).
 */
function initPrescriptionManagement() {
  // Lấy config từ window object (được set từ Blade template)
  const config = window.patientRecordConfig || {};
  const initialIndex = config.prescriptionIndex || 1;
  
  let prescriptionIndex = initialIndex;
  const body = document.getElementById('prescription-items-body');
  const addBtn = document.getElementById('add-prescription-row');

  // Khởi tạo Select2 cho các dropdown thuốc hiện có
  if (window.jQuery && typeof $.fn.select2 === 'function') {
    $('.medicine-select').select2({
      width: '100%',
      placeholder: '-- Chọn thuốc --',
      allowClear: true
    });
  }

  if (!addBtn || !body) {
    return;
  }

  // Xử lý thêm dòng thuốc mới
  addBtn.addEventListener('click', function() {
    const firstRow = body.querySelector('.prescription-row');
    if (!firstRow) {
      return;
    }

    // Hủy select2 trên tất cả dropdown thuốc hiện tại để tránh clone markup của select2
    if (window.jQuery && typeof $.fn.select2 === 'function') {
      $('.medicine-select').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
          $(this).select2('destroy');
        }
      });
    }

    // Clone dòng đầu tiên
    const newRow = firstRow.cloneNode(true);

    // Cập nhật name với index mới và xóa giá trị cũ
    newRow.querySelectorAll('input, select').forEach(function(el) {
      if (el.name) {
        el.name = el.name.replace(/prescription_items\[[0-9]+\]/, 'prescription_items[' + prescriptionIndex + ']');
      }
      if (el.tagName === 'SELECT') {
        el.selectedIndex = 0;
      } else {
        el.value = '';
      }
    });

    body.appendChild(newRow);

    // Khởi tạo lại select2 cho TẤT CẢ dropdown thuốc
    if (window.jQuery && typeof $.fn.select2 === 'function') {
      $('.medicine-select').select2({
        width: '100%',
        placeholder: '-- Chọn thuốc --',
        allowClear: true
      });
    }

    prescriptionIndex++;
  });

  // Xử lý xóa dòng thuốc
  body.addEventListener('click', function(e) {
    if (e.target.closest('.remove-prescription-row')) {
      const rows = body.querySelectorAll('.prescription-row');
      if (rows.length <= 1) {
        return; // Luôn giữ ít nhất 1 dòng
      }
      
      const row = e.target.closest('.prescription-row');
      if (row) {
        // Hủy select2 trên row bị xóa
        if (window.jQuery && typeof $.fn.select2 === 'function') {
          const select = row.querySelector('.medicine-select');
          if (select && $(select).hasClass('select2-hidden-accessible')) {
            $(select).select2('destroy');
          }
        }
        
        row.remove();
      }
    }
  });
}