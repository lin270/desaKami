@php
    // @yield sudah meng-escape HTML; di-decode dulu agar tidak ter-escape dua kali
    $pageTitle = html_entity_decode(trim($__env->yieldContent('title')), ENT_QUOTES);
    $fullTitle = $pageTitle !== ''
        ? $pageTitle.' - '.$site['site_name']
        : $site['site_name'].' - '.$site['site_tagline'];
    $pageDesc = html_entity_decode(trim($__env->yieldContent('description')), ENT_QUOTES)
        ?: 'Website resmi '.$site['site_name'].': berita, pengumuman, agenda, dan informasi pelayanan desa.';
    $canonical = html_entity_decode(trim($__env->yieldContent('canonical')), ENT_QUOTES) ?: url()->current();

    // [label, path|null, patterns|children]. path null = grup dropdown, children = [label, path, patterns].
    $menu = [
        ['Beranda', '/', ['/']],
        ['Berita', '/berita', ['berita*', 'kategori*', 'search']],
        ['Peta Desa', '/peta-desa', ['peta-desa*']],
        ['Profil', null, [
            ['Profil Desa', '/profil', ['profil*']],
            ['Pemerintahan', '/pemerintahan', ['pemerintahan*']],
            ['Potensi Desa', '/potensi', ['potensi*']],
        ]],
        ['Informasi', null, [
            ['Informasi Layanan', '/informasi', ['informasi*']],
            ['Pengumuman', '/pengumuman', ['pengumuman*']],
            ['Kalender Warga', '/agenda', ['agenda*']],
            ['Barang Hilang & Ditemukan', '/barang-hilang', ['barang-hilang*']],
        ]],
        ['APBDes', '/apbdes', ['apbdes*']],
        ['Galeri', '/galeri', ['galeri*']],
    ];

    $isActive = fn (array $patterns) => request()->is(...array_map(fn ($p) => ltrim($p, '/') ?: '/', $patterns));

    // Daftar datar untuk footer
    $flatMenu = collect($menu)->flatMap(fn ($m) => $m[1] === null ? $m[2] : [$m])->all();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ $canonical }}">

    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="{{ $site['site_name'] }}">
    <meta property="og:title" content="{{ $pageTitle !== '' ? $pageTitle : $fullTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="id_ID">
    @hasSection('image')
        <meta property="og:image" content="@yield('image')">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="@yield('image')">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $pageTitle !== '' ? $pageTitle : $fullTitle }}">
    <meta name="twitter:description" content="{{ $pageDesc }}">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <div class="topbar">
        <div class="container topbar-inner">
            <span>{{ now()->translatedFormat('l, j F Y') }}</span>
            @if (!empty($site['phone']))
                <span class="hide-sm">Hubungi kami: {{ $site['phone'] }}</span>
            @endif
        </div>
    </div>

    <header class="site-header">
        <div class="container header-main">
            <a href="{{ url('/') }}" class="brand" aria-label="{{ $site['site_name'] }} - Beranda">
                @if ($logoUrl)
                    <img class="brand-logo" src="{{ $logoUrl }}" alt="Logo {{ $site['site_name'] }}" width="48" height="48">
                @else
                    <span class="brand-mark" aria-hidden="true">M</span>
                @endif
                <span class="brand-text">
                    <span class="brand-name">{{ $site['site_name'] }}</span>
                    <span class="brand-tagline">{{ $site['site_tagline'] }}</span>
                </span>
            </a>

            <div class="header-actions">
                <button type="button" class="icon-btn" id="search-toggle" aria-label="Cari berita" aria-expanded="false" aria-controls="search-panel">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </button>

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="btn-login">Dashboard</a>
                @else
                    <a href="{{ route('admin.login') }}" class="btn-login"><span class="hide-xs">Login </span>Admin</a>
                @endauth

                <button type="button" class="icon-btn nav-toggle" id="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="main-nav">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div class="search-panel" id="search-panel" hidden>
            <div class="container">
                <form action="{{ url('/search') }}" method="GET" role="search" class="search-form">
                    <label for="q" class="visually-hidden">Cari berita</label>
                    <input type="search" id="q" name="q" placeholder="Cari berita desa..." value="{{ request('q') }}" required minlength="2" maxlength="100">
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>
        </div>

        <nav class="main-nav" id="main-nav" aria-label="Menu utama">
            <div class="container">
                <ul>
                    @foreach ($menu as [$label, $path, $extra])
                        @if ($path === null)
                            <li @class(['has-sub', 'active-group' => collect($extra)->contains(fn ($c) => $isActive($c[2]))])>
                                <button type="button" class="nav-drop" aria-expanded="false" aria-haspopup="true">{{ $label }}<span class="caret" aria-hidden="true"></span></button>
                                <ul class="sub-nav">
                                    @foreach ($extra as [$childLabel, $childPath, $childPatterns])
                                        <li><a href="{{ url($childPath) }}" @class(['active' => $isActive($childPatterns)])>{{ $childLabel }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ url($path) }}" @class(['active' => $isActive($extra)])>{{ $label }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>

    <main id="konten">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <h2 class="footer-brand">{{ $site['site_name'] }}</h2>
                <p>{{ $site['site_tagline'] }}</p>
                @if (!empty($site['address']))
                    <p>{{ $site['address'] }}</p>
                @endif
            </div>

            <div>
                <h3>Navigasi</h3>
                <ul>
                    @foreach ($flatMenu as [$label, $path])
                        <li><a href="{{ url($path) }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3>Kontak</h3>
                <ul>
                    @if (!empty($site['phone']))<li>Telepon: {{ $site['phone'] }}</li>@endif
                    @if (!empty($site['email']))<li>Email: <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>@endif
                    @if (!empty($site['services_hours']))<li>Jam pelayanan: {{ $site['services_hours'] }}</li>@endif
                </ul>
            </div>

            <div>
                <h3>Sosial Media</h3>
                @if (!empty($site['facebook']) || !empty($site['instagram']) || !empty($site['youtube']))
                    <ul>
                        @if (!empty($site['facebook']))<li><a href="{{ $site['facebook'] }}" rel="noopener" target="_blank">Facebook</a></li>@endif
                        @if (!empty($site['instagram']))<li><a href="{{ $site['instagram'] }}" rel="noopener" target="_blank">Instagram</a></li>@endif
                        @if (!empty($site['youtube']))<li><a href="{{ $site['youtube'] }}" rel="noopener" target="_blank">YouTube</a></li>@endif
                    </ul>
                @else
                    <p class="muted">Belum ada akun sosial media. Isi di menu Pengaturan admin.</p>
                @endif
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                &copy; {{ date('Y') }} {{ $site['site_name'] }}. Hak cipta dilindungi.
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
