/* Desa Mondokan - interaksi ringan panel admin */
(function () {
    'use strict';

    var body = document.body;
    var toggle = document.getElementById('side-toggle');
    var overlay = document.getElementById('side-overlay');

    function setSide(open) {
        body.classList.toggle('side-open', open);
        if (toggle) toggle.setAttribute('aria-expanded', String(open));
    }

    if (toggle) toggle.addEventListener('click', function () { setSide(!body.classList.contains('side-open')); });
    if (overlay) overlay.addEventListener('click', function () { setSide(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setSide(false); });

    // Konfirmasi sebelum menghapus: <form data-confirm="Pesan">
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute && e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) e.preventDefault();
    });

    // Kirim form otomatis saat filter berubah: <select data-autosubmit>
    document.addEventListener('change', function (e) {
        var t = e.target;
        if (t && t.matches && t.matches('[data-autosubmit]') && t.form) t.form.submit();
    });

    // Pratinjau gambar sebelum diunggah: <input type="file" data-preview="id-img">
    document.querySelectorAll('input[type=file][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var img = document.getElementById(input.getAttribute('data-preview'));
            var file = input.files && input.files[0];
            if (!img || !file) return;
            img.src = URL.createObjectURL(file);
            img.hidden = false;
        });
    });
})();
