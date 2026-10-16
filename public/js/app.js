/* Desa Klepu - interaksi ringan: navbar, menu mobile, pencarian, reveal saat scroll */
(function () {
    'use strict';

    var root = document.documentElement;
    var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var header = document.getElementById('site-header');
    var navBtn = document.getElementById('nav-toggle');
    var nav = document.getElementById('main-nav');
    var searchBtn = document.getElementById('search-toggle');
    var panel = document.getElementById('search-panel');

    /* Header berubah setelah di-scroll */
    function onScroll() {
        if (header) header.classList.toggle('is-scrolled', window.scrollY > 40);
    }
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });

    /* Menu mobile */
    function setNav(open) {
        if (!nav || !navBtn) return;
        nav.classList.toggle('is-open', open);
        document.body.classList.toggle('nav-open', open);
        if (header) header.classList.toggle('nav-active', open);
        navBtn.setAttribute('aria-expanded', String(open));
        navBtn.setAttribute('aria-label', open ? 'Tutup menu' : 'Buka menu');
    }

    if (navBtn && nav) {
        navBtn.addEventListener('click', function () {
            setNav(!nav.classList.contains('is-open'));
        });
        nav.addEventListener('click', function (e) {
            var a = e.target.closest ? e.target.closest('a') : null;
            if (a) setNav(false);
        });
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 1000) setNav(false);
        });
    }

    /* Panel pencarian */
    function closeSearch() {
        if (!panel || !searchBtn) return;
        panel.setAttribute('hidden', '');
        searchBtn.setAttribute('aria-expanded', 'false');
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

    /* Dropdown (Profil Desa, Layanan): klik untuk buka/tutup; hover/fokus bekerja lewat CSS di desktop */
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
        if (e.key !== 'Escape') return;
        setNav(false);
        closeSearch();
        closeDrops(null);
        var ae = document.activeElement;
        if (ae && ae.classList && ae.classList.contains('nav-drop')) ae.blur();
    });

    /* Tombol "Salin tautan": <button data-copy="url"> */
    [].slice.call(document.querySelectorAll('[data-copy]')).forEach(function (btn) {
        var label = btn.textContent;
        btn.addEventListener('click', function () {
            var url = btn.getAttribute('data-copy');
            function done(ok) {
                btn.textContent = ok ? 'Tautan disalin' : 'Gagal menyalin';
                setTimeout(function () { btn.textContent = label; }, 2000);
            }
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(url).then(function () { done(true); }, function () { done(false); });
            } else {
                var t = document.createElement('textarea');
                t.value = url; document.body.appendChild(t); t.select();
                var ok = false; try { ok = document.execCommand('copy'); } catch (e) {}
                document.body.removeChild(t); done(ok);
            }
        });
    });

    /* Reveal saat scroll. Tanpa JS atau dengan reduced-motion, semua konten langsung tampil. */
    var items = [].slice.call(document.querySelectorAll('.reveal'));
    if (!items.length || reduceMotion || !('IntersectionObserver' in window)) return;

    root.classList.add('js');
    var vh = window.innerHeight || 800;

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
            if (en.isIntersecting) {
                en.target.classList.add('in');
                io.unobserve(en.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach(function (el) {
        if (el.getBoundingClientRect().top < vh) {
            el.classList.add('in-now');
        } else {
            io.observe(el);
        }
    });
})();
