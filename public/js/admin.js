/* Desa Klepu - interaksi ringan panel admin */
(function () {
    'use strict';

    var body = document.body;
    var toggle = document.getElementById('side-toggle');
    var overlay = document.getElementById('side-overlay');
    var collapse = document.getElementById('side-collapse');
    var KEY = 'klepu.admin.sidebar';

    function setSide(open) {
        body.classList.toggle('side-open', open);
        if (toggle) toggle.setAttribute('aria-expanded', String(open));
    }

    if (toggle) toggle.addEventListener('click', function () { setSide(!body.classList.contains('side-open')); });
    if (overlay) overlay.addEventListener('click', function () { setSide(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setSide(false); });

    /* Sidebar dapat diciutkan di desktop; pilihan diingat di peramban (opsional, gagal tanpa efek) */
    function setCollapsed(on) {
        body.classList.toggle('side-collapsed', on);
        if (collapse) {
            collapse.setAttribute('aria-pressed', String(on));
            collapse.setAttribute('aria-label', on ? 'Lebarkan menu samping' : 'Ciutkan menu samping');
        }
        try { localStorage.setItem(KEY, on ? '1' : '0'); } catch (e) { /* abaikan */ }
    }
    try { if (localStorage.getItem(KEY) === '1') setCollapsed(true); } catch (e) { /* abaikan */ }
    if (collapse) collapse.addEventListener('click', function () { setCollapsed(!body.classList.contains('side-collapsed')); });

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

    // Notifikasi sukses/gagal: tombol tutup; pesan sukses hilang otomatis
    document.querySelectorAll('.alert').forEach(function (a) {
        var x = document.createElement('button');
        x.type = 'button';
        x.className = 'x';
        x.setAttribute('aria-label', 'Tutup pesan');
        x.innerHTML = '&times;';
        x.addEventListener('click', function () { a.remove(); });
        a.appendChild(x);
        var ms = parseInt(a.getAttribute('data-dismiss'), 10);
        if (ms) setTimeout(function () { if (a.parentNode) a.remove(); }, ms);
    });
})();
