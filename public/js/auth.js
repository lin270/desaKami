/* Desa Klepu - tampilkan/sembunyikan password pada form login */
(function () {
    'use strict';
    var btn = document.getElementById('pw-toggle');
    var input = document.getElementById('password');
    if (!btn || !input) return;
    btn.hidden = false;
    btn.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Sembunyikan' : 'Tampilkan';
        btn.setAttribute('aria-pressed', String(show));
    });
})();
