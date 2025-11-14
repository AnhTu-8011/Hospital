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
});