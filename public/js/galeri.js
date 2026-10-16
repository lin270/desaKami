/* Desa Klepu - lightbox galeri (memakai elemen <dialog> bawaan browser) */
(function () {
    'use strict';

    var links = [].slice.call(document.querySelectorAll('[data-lightbox]'));
    var dlg = document.getElementById('lightbox');
    if (!links.length || !dlg || typeof dlg.showModal !== 'function') return;

    var img = dlg.querySelector('img');
    var cap = dlg.querySelector('.lb-caption');
    var count = dlg.querySelector('.lb-count');
    var idx = 0;

    function show(i) {
        idx = (i + links.length) % links.length;
        var a = links[idx];
        var text = a.getAttribute('data-caption') || '';
        img.src = a.href;
        img.alt = text;
        cap.textContent = text;
        if (count) count.textContent = (idx + 1) + ' / ' + links.length;
    }

    links.forEach(function (a, i) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            show(i);
            dlg.showModal();
        });
    });

    dlg.querySelector('.lb-close').addEventListener('click', function () { dlg.close(); });
    dlg.querySelector('.lb-prev').addEventListener('click', function () { show(idx - 1); });
    dlg.querySelector('.lb-next').addEventListener('click', function () { show(idx + 1); });

    // Klik area gelap di luar foto menutup lightbox
    dlg.addEventListener('click', function (e) {
        if (e.target === dlg || e.target.classList.contains('lb-inner')) dlg.close();
    });

    // Geser jari kiri/kanan untuk berpindah foto
    var x0 = null;
    dlg.addEventListener('touchstart', function (e) { x0 = e.changedTouches[0].clientX; }, { passive: true });
    dlg.addEventListener('touchend', function (e) {
        if (x0 === null) return;
        var dx = e.changedTouches[0].clientX - x0;
        x0 = null;
        if (Math.abs(dx) > 50) show(idx + (dx < 0 ? 1 : -1));
    }, { passive: true });

    document.addEventListener('keydown', function (e) {
        if (!dlg.open) return;
        if (e.key === 'ArrowLeft') show(idx - 1);
        if (e.key === 'ArrowRight') show(idx + 1);
    });
})();
