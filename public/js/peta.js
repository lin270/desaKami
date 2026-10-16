/* Desa Klepu - peta interaktif bergaya peta daring (Leaflet + OpenStreetMap).
   Hanya memakai lokasi & koordinat dari database; tidak ada marker fiktif. */
(function () {
    'use strict';

    var el = document.getElementById('village-map');
    var stateBox = document.getElementById('map-state');
    if (!el) return;

    function reduce() {
        return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    }

    function showState(title, text) {
        if (!stateBox) return;
        stateBox.textContent = '';
        var d = document.createElement('div');
        var s = document.createElement('strong');
        s.textContent = title;
        d.appendChild(s);
        d.appendChild(document.createTextNode(text));
        stateBox.appendChild(d);
        stateBox.hidden = false;
    }

    var items = [].slice.call(document.querySelectorAll('.location-item'));
    /* Foto yang gagal dimuat: sembunyikan, ilustrasi pengganti tetap tampil */
    document.addEventListener('error', function (e) {
        var t = e.target;
        if (t && t.tagName === 'IMG' && t.closest && t.closest('.gm')) t.style.display = 'none';
    }, true);

    if (!window.L) {
        showState('Peta tidak dapat dimuat', 'Periksa koneksi internet Anda. Daftar tempat tetap dapat dibaca.');
        return;
    }

    var L = window.L;
    var map = L.map(el, { zoomControl: false, scrollWheelZoom: true }).setView([-2.5, 118], 5);
    L.control.zoom({ position: 'bottomright', zoomInTitle: 'Perbesar', zoomOutTitle: 'Perkecil' }).addTo(map);

    var tilesFailed = 0;
    var tilesOk = false;
    var tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    tiles.on('tileload', function () {
        tilesOk = true;
        if (stateBox) stateBox.hidden = true;
    });
    tiles.on('tileerror', function () {
        tilesFailed++;
        if (!tilesOk && tilesFailed >= 4) showState('Peta dasar belum termuat', 'Marker dan daftar tetap bekerja. Muat ulang halaman bila koneksi sudah pulih.');
    });

    /* Fallback: jangan biarkan lapisan "memuat" menutupi peta selamanya */
    setTimeout(function () { if (stateBox && !stateBox.hidden && tilesFailed < 4) stateBox.hidden = true; }, 6000);

    function pinIcon(active) {
        var size = active ? 44 : 34;
        return L.divIcon({
            className: 'gm-pin' + (active ? ' is-active' : ''),
            html: '<svg viewBox="0 0 24 32" width="' + size + '" height="' + Math.round(size * 32 / 24) + '" aria-hidden="true"><path d="M12 0C5.4 0 0 5.2 0 11.7 0 20 12 32 12 32s12-12 12-20.3C24 5.2 18.6 0 12 0Z" fill="currentColor"/><circle cx="12" cy="11.5" r="4.2" fill="#F5F1E8"/></svg>',
            iconSize: [size, Math.round(size * 32 / 24)],
            iconAnchor: [size / 2, Math.round(size * 32 / 24)],
            popupAnchor: [0, -Math.round(size * 32 / 24)]
        });
    }

    var markers = new Map();
    var group = L.featureGroup();
    var activeBtn = null;

    function dataOf(b) {
        return {
            id: b.dataset.id, name: b.dataset.name, category: b.dataset.category,
            description: b.dataset.description, address: b.dataset.address, image: b.dataset.image,
            lat: Number(b.dataset.lat), lng: Number(b.dataset.lng)
        };
    }

    /* ---------- Pratinjau foto di atas marker ---------- */
    var panel = document.getElementById('gm-panel');
    var scroller = document.getElementById('gm-scroll');
    var isDesktop = function () { return window.innerWidth >= 761; };

    function popupContent(d) {
        var box = document.createElement('div');

        var photo = document.createElement('div');
        photo.className = 'gm-pop-photo';
        if (d.image) {
            var img = document.createElement('img');
            img.src = d.image;
            img.alt = 'Foto ' + d.name;
            photo.appendChild(img);
        } else {
            photo.textContent = 'Belum ada foto';
        }
        box.appendChild(photo);

        var body = document.createElement('div');
        body.className = 'gm-pop-body';
        var cat = document.createElement('span');
        cat.className = 'gm-pop-cat';
        cat.textContent = d.category;
        var name = document.createElement('strong');
        name.textContent = d.name;
        body.appendChild(cat);
        body.appendChild(name);
        if (d.address) {
            var addr = document.createElement('p');
            addr.className = 'gm-pop-addr';
            addr.textContent = d.address;
            body.appendChild(addr);
        }
        var route = document.createElement('a');
        route.className = 'gm-pop-act';
        route.target = '_blank';
        route.rel = 'noopener';
        route.href = 'https://www.google.com/maps/dir/?api=1&destination=' + encodeURIComponent(d.lat + ',' + d.lng);
        route.textContent = 'Rute';
        body.appendChild(route);
        box.appendChild(body);
        return box;
    }

    function setActive(b) {
        activeBtn = b;
        items.forEach(function (x) { x.classList.toggle('is-active', x === b); });
        markers.forEach(function (m, id) {
            var on = !!b && b.dataset.id === id;
            m.setIcon(pinIcon(on));
            m.setZIndexOffset(on ? 1000 : 0);
        });
    }

    /* Klik lokasi: peta zoom ke marker, lalu foto muncul di atas marker itu.
       Daftar di kiri tidak berubah (tetap menampilkan semua lokasi). */
    function select(b) {
        var m = markers.get(b.dataset.id);
        if (!m) return;
        setActive(b);
        if (!isDesktop()) {
            panel.classList.remove('is-expanded');
            setHandle(false);
        }

        var opened = false;
        function openIt() {
            if (opened) return;
            opened = true;
            m.openPopup();
        }
        map.once('moveend', openIt);
        map.flyTo(m.getLatLng(), Math.max(map.getZoom(), 17), { duration: reduce() ? 0 : 0.7 });
        setTimeout(openIt, 1200); // cadangan bila peta sudah berada di posisi tersebut
    }

    items.forEach(function (b) {
        var d = dataOf(b);
        if (!isFinite(d.lat) || !isFinite(d.lng) || d.lat < -90 || d.lat > 90 || d.lng < -180 || d.lng > 180) {
            b.parentNode.hidden = true; // koordinat tidak valid: jangan dipaksakan tampil
            return;
        }
        var m = L.marker([d.lat, d.lng], { icon: pinIcon(false), title: d.name, keyboard: true, riseOnHover: true });
        m.bindTooltip(d.name, { direction: 'top', offset: [0, -30], className: 'gm-tip' });
        m.bindPopup(popupContent(d), {
            className: 'gm-popup',
            maxWidth: 260,
            minWidth: 260,
            offset: [0, -4],
            autoPan: true,
            autoPanPaddingTopLeft: L.point(isDesktop() ? 400 : 20, 70),
            autoPanPaddingBottomRight: L.point(20, isDesktop() ? 20 : 300)
        });
        m.on('click', function () { setActive(b); b.scrollIntoView({ block: 'nearest' }); });
        group.addLayer(m);
        markers.set(b.dataset.id, m);
        b.addEventListener('click', function () { select(b); });
    });

    group.addTo(map);

    function fitAll() {
        var layers = group.getLayers();
        if (!layers.length) return;
        var opts = {
            maxZoom: 16,
            paddingTopLeft: window.innerWidth >= 761 ? [390, 40] : [30, 90],
            paddingBottomRight: window.innerWidth >= 761 ? [40, 40] : [30, 280]
        };
        if (layers.length === 1) {
            map.setView(layers[0].getLatLng(), 16);
        } else {
            map.fitBounds(group.getBounds(), opts);
        }
    }
    map.whenReady(function () {
        map.invalidateSize();
        if (markers.size) {
            fitAll();
        } else if (items.length) {
            showState('Koordinat belum valid', 'Lokasi sudah tercatat, tetapi koordinatnya belum dapat ditampilkan. Periksa latitude dan longitude di dashboard.');
        } else {
            showState('Belum ada lokasi di peta', 'Tambahkan lokasi, koordinat, dan aktifkan opsi “Tampilkan di halaman Peta Desa” melalui dashboard admin.');
        }
    });

    if (!markers.size) {
        // Peta dasar tetap tampil meskipun belum ada lokasi valid di database.
        // Fitur pencarian/filter tidak perlu dipasang jika daftar lokasi kosong.
        if (!items.length) return;
        return;
    }

    map.on('popupclose', function () { setActive(null); });

    /* ---------- Pencarian + kategori ---------- */
    var search = document.getElementById('location-search');
    var clearBtn = document.getElementById('gm-clear');
    var chips = [].slice.call(document.querySelectorAll('.gm-chip'));
    var count = document.getElementById('location-count');
    var none = document.getElementById('location-none');
    var resetBtn = document.getElementById('map-reset');
    var category = '';
    var timer = null;

    function applyFilter() {
        var q = search.value.trim().toLowerCase();
        var n = 0;
        map.closePopup();
        group.clearLayers();
        items.forEach(function (b) {
            var m = markers.get(b.dataset.id);
            if (!m) return;
            var hay = (b.dataset.name + ' ' + b.dataset.category + ' ' + b.dataset.address).toLowerCase();
            var ok = (!q || hay.indexOf(q) !== -1) && (!category || b.dataset.category === category);
            b.parentNode.hidden = !ok;
            if (ok) { group.addLayer(m); n++; }
        });
        count.textContent = String(n);
        if (none) none.classList.toggle('is-hidden', n !== 0);
        clearBtn.hidden = !q;
        resetBtn.hidden = !(q || category);
        if (n) fitAll();
    }

    search.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(applyFilter, 150);
        clearBtn.hidden = !search.value;
    });
    search.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            var first = items.filter(function (b) { return !b.parentNode.hidden && markers.has(b.dataset.id); })[0];
            if (first) select(first);
        }
    });
    clearBtn.addEventListener('click', function () { search.value = ''; applyFilter(); search.focus(); });

    chips.forEach(function (c) {
        c.addEventListener('click', function () {
            category = c.dataset.category;
            chips.forEach(function (x) { x.classList.toggle('is-on', x === c); });
            applyFilter();
        });
    });

    resetBtn.addEventListener('click', function () {
        search.value = '';
        category = '';
        chips.forEach(function (x) { x.classList.toggle('is-on', x.dataset.category === ''); });
        map.closePopup();
        applyFilter();
    });

    /* ---------- Bottom sheet (mobile) ---------- */
    var handle = document.getElementById('gm-handle');
    function setHandle(expanded) {
        handle.setAttribute('aria-expanded', String(expanded));
        handle.setAttribute('aria-label', expanded ? 'Ciutkan daftar lokasi' : 'Perluas daftar lokasi');
    }
    handle.addEventListener('click', function () {
        var on = panel.classList.toggle('is-expanded');
        setHandle(on);
        setTimeout(function () { map.invalidateSize(); }, 280);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') map.closePopup();
    });

    window.addEventListener('resize', function () { map.invalidateSize(); });
})();
