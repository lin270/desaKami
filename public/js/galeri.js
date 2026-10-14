/* Desa Mondokan - lightbox galeri (memakai elemen <dialog> bawaan browser) */
(function () {
    'use strict';

    var links = [].slice.call(document.querySelectorAll('[data-lightbox]'));
    var dlg = document.getElementById('lightbox');
    if (!links.length || !dlg || typeof dlg.showModal !== 'function') return;

    var img = dlg.querySelector('img');
    var cap = dlg.querySelector('.lb-caption');
    var idx = 0;

    function show(i) {
        idx = (i + links.length) % links.length;
        var a = links[idx];
        var text = a.getAttribute('data-caption') || '';
        img.src = a.href;
        img.alt = text;
        cap.textContent = text;
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

    document.addEventListener('keydown', function (e) {
        if (!dlg.open) return;
        if (e.key === 'ArrowLeft') show(idx - 1);
        if (e.key === 'ArrowRight') show(idx + 1);
    });
})();
