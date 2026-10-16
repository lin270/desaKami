/* Desa Klepu - Kalender Warga: kalender bulanan (minggu mulai Senin).
   Data dibaca dari <script type="application/json" id="calendar-events"> (dari database). */
(function () {
    'use strict';

    var node = document.getElementById('calendar-events');
    var grid = document.getElementById('cal-grid');
    if (!node || !grid) return;

    var events = [];
    try { events = JSON.parse(node.textContent || '[]'); } catch (e) { events = []; }

    var MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    var DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    var byDate = {};
    events.forEach(function (ev) { (byDate[ev.date] = byDate[ev.date] || []).push(ev); });
    Object.keys(byDate).forEach(function (k) {
        byDate[k].sort(function (a, b) { return (a.start || '').localeCompare(b.start || ''); });
    });

    function key(d) {
        var m = d.getMonth() + 1, day = d.getDate();
        return d.getFullYear() + '-' + (m < 10 ? '0' : '') + m + '-' + (day < 10 ? '0' : '') + day;
    }
    function parseKey(k) { var p = k.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); }
    function el(tag, cls, text) {
        var n = document.createElement(tag);
        if (cls) n.className = cls;
        if (text != null) n.textContent = text;
        return n;
    }

    var today = new Date(); today.setHours(0, 0, 0, 0);
    var view = new Date(today.getFullYear(), today.getMonth(), 1);
    var selected = key(today);
    var focusAfter = false;

    var elYear = document.getElementById('cal-year');
    var elMonth = document.getElementById('cal-month');

    function render() {
        elYear.textContent = view.getFullYear();
        elMonth.textContent = MONTHS[view.getMonth()];
        grid.textContent = '';

        var first = new Date(view.getFullYear(), view.getMonth(), 1);
        var offset = (first.getDay() + 6) % 7; // Senin = 0
        var start = new Date(first); start.setDate(1 - offset);
        var rows = Math.ceil((offset + new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate()) / 7);

        for (var i = 0; i < rows * 7; i++) {
            var d = new Date(start); d.setDate(start.getDate() + i);
            var k = key(d);
            var list = byDate[k] || [];
            var out = d.getMonth() !== view.getMonth();
            var cls = 'cal-day';
            if (out) cls += ' is-out';
            if (k === key(today)) cls += ' is-today';
            if (k === selected) cls += ' is-sel';
            if (i % 7 > 4) cls += ' is-we';
            if (list.length) cls += ' has-ev';

            var b = el('button', cls);
            b.type = 'button';
            b.setAttribute('role', 'gridcell');
            b.dataset.date = k;
            b.tabIndex = k === selected ? 0 : -1;
            b.setAttribute('aria-label', DAYS[d.getDay()] + ', ' + d.getDate() + ' ' + MONTHS[d.getMonth()] + ' ' + d.getFullYear() + (list.length ? ', ' + list.length + ' agenda' : ''));
            if (k === selected) b.setAttribute('aria-selected', 'true');

            b.appendChild(el('span', 'cal-num', d.getDate()));

            if (list.length) {
                var pills = el('span', 'cal-evs');
                list.slice(0, 2).forEach(function (ev, idx) {
                    var p = el('span', 'cal-ev c' + (idx % 3));
                    if (ev.start) p.appendChild(el('b', null, ev.start));
                    p.appendChild(document.createTextNode(' ' + ev.title));
                    pills.appendChild(p);
                });
                if (list.length > 2) pills.appendChild(el('span', 'cal-more', '+' + (list.length - 2) + ' lagi'));
                b.appendChild(pills);

                var dots = el('span', 'cal-dots');
                list.slice(0, 3).forEach(function (ev, idx) { dots.appendChild(el('i', 'c' + (idx % 3))); });
                b.appendChild(dots);
            }
            grid.appendChild(b);
        }

        renderSelected();
        if (focusAfter) {
            var f = grid.querySelector('.cal-day.is-sel');
            if (f) f.focus();
            focusAfter = false;
        }
    }

    function renderSelected() {
        var d = parseKey(selected);
        document.getElementById('sel-dow').textContent = DAYS[d.getDay()];
        document.getElementById('sel-day').textContent = d.getDate();
        document.getElementById('sel-my').textContent = MONTHS[d.getMonth()] + ' ' + d.getFullYear();

        var box = document.getElementById('sel-list');
        box.textContent = '';
        var list = byDate[selected] || [];
        if (!list.length) {
            box.appendChild(el('p', 'cal-none', 'Tidak ada agenda pada tanggal ini.'));
            return;
        }
        list.forEach(function (ev, idx) {
            var a = el('article', 'cal-item c' + (idx % 3));
            a.appendChild(el('span', 'cal-item-time', (ev.start || '') + (ev.end ? ' - ' + ev.end : '') + (ev.start ? ' WIB' : '')));
            a.appendChild(el('h3', null, ev.title));
            if (ev.location) a.appendChild(el('p', 'cal-item-loc', ev.location));
            if (ev.description) a.appendChild(el('p', 'cal-item-desc', ev.description));
            box.appendChild(a);
        });
    }

    function go(deltaMonths) {
        view = new Date(view.getFullYear(), view.getMonth() + deltaMonths, 1);
        render();
    }

    document.getElementById('cal-prev').addEventListener('click', function () { go(-1); });
    document.getElementById('cal-next').addEventListener('click', function () { go(1); });
    document.getElementById('cal-today').addEventListener('click', function () {
        view = new Date(today.getFullYear(), today.getMonth(), 1);
        selected = key(today);
        render();
    });

    grid.addEventListener('click', function (e) {
        var b = e.target.closest ? e.target.closest('.cal-day') : null;
        if (!b) return;
        selected = b.dataset.date;
        var d = parseKey(selected);
        if (d.getMonth() !== view.getMonth()) view = new Date(d.getFullYear(), d.getMonth(), 1);
        render();
    });

    /* Navigasi papan ketik: panah = pindah hari/minggu, PageUp/PageDown = bulan */
    grid.addEventListener('keydown', function (e) {
        var step = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 }[e.key];
        var d = parseKey(selected);
        if (step) {
            d.setDate(d.getDate() + step);
        } else if (e.key === 'PageDown') {
            d.setMonth(d.getMonth() + 1);
        } else if (e.key === 'PageUp') {
            d.setMonth(d.getMonth() - 1);
        } else { return; }
        e.preventDefault();
        selected = key(d);
        view = new Date(d.getFullYear(), d.getMonth(), 1);
        focusAfter = true;
        render();
    });

    render();
})();
