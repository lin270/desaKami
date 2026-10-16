/* Desa Klepu - Ruang Aspirasi: penghitung karakter + cegah kirim ganda */
(function () {
    'use strict';
    var ta = document.getElementById('message');
    var out = document.getElementById('msg-count');
    if (!ta || !out) return;

    function update() { out.textContent = String(ta.value.length); }
    ta.addEventListener('input', update);
    update();

    var form = ta.form;
    if (form) {
        form.addEventListener('submit', function () {
            var btn = form.querySelector('button[type=submit]');
            if (btn) { btn.disabled = true; btn.textContent = 'Mengirim...'; }
        });
    }
})();
