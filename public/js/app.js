/* Desa Mondokan - interaksi ringan: menu hamburger dan panel pencarian */
(function () {
    'use strict';

    var navBtn = document.getElementById('nav-toggle');
    var nav = document.getElementById('main-nav');
    var searchBtn = document.getElementById('search-toggle');
    var panel = document.getElementById('search-panel');

    function closeNav() {
        if (!nav || !navBtn) return;
        nav.classList.remove('is-open');
        navBtn.setAttribute('aria-expanded', 'false');
    }

    function closeSearch() {
        if (!panel || !searchBtn) return;
        panel.setAttribute('hidden', '');
        searchBtn.setAttribute('aria-expanded', 'false');
    }

    if (navBtn && nav) {
        navBtn.addEventListener('click', function () {
            var open = nav.classList.toggle('is-open');
            navBtn.setAttribute('aria-expanded', String(open));
        });
    }

    if (searchBtn && panel) {
        searchBtn.addEventListener('click', function () {
            var opening = panel.hasAttribute('hidden');
            if (opening) {
                panel.removeAttribute('hidden');
                var input = panel.querySelector('input');
                if (input) input.focus();
            } else {
                panel.setAttribute('hidden', '');
            }
            searchBtn.setAttribute('aria-expanded', String(opening));
        });
    }

    // Dropdown menu (Profil, Informasi): klik untuk buka/tutup; hover juga bekerja lewat CSS di desktop
    var drops = [].slice.call(document.querySelectorAll('.nav-drop'));

    function closeDrops(except) {
        drops.forEach(function (b) {
            if (b === except) return;
            b.setAttribute('aria-expanded', 'false');
            b.parentNode.classList.remove('open');
        });
    }

    drops.forEach(function (b) {
        b.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = b.getAttribute('aria-expanded') !== 'true';
            closeDrops(b);
            b.setAttribute('aria-expanded', String(open));
            b.parentNode.classList.toggle('open', open);
        });
    });

    document.addEventListener('click', function () { closeDrops(null); });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeNav();
            closeSearch();
            closeDrops(null);
            // :focus-within menjaga dropdown tetap terbuka selama tombol fokus
            var ae = document.activeElement;
            if (ae && ae.classList && ae.classList.contains('nav-drop')) ae.blur();
        }
    });
})();
