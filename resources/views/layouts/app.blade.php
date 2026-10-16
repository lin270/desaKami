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
        ['Profil Desa', null, [
            ['Profil Desa', '/profil', ['profil*']],
            ['Pemerintahan', '/pemerintahan', ['pemerintahan*']],
            ['Potensi Desa', '/potensi', ['potensi*']],
        ]],
        ['Berita', '/berita', ['berita*', 'kategori*', 'search']],
        ['Peta Desa', '/peta-desa', ['peta-desa*']],
        ['Galeri', '/galeri', ['galeri*']],
        ['Layanan', null, [
            ['Informasi Layanan', '/informasi', ['informasi*']],
            ['Pengumuman', '/pengumuman', ['pengumuman*']],
            ['Kalender Warga', '/agenda', ['agenda*']],
            ['Ruang Aspirasi', '/aspirasi', ['aspirasi*']],
            ['Barang Hilang & Ditemukan', '/barang-hilang', ['barang-hilang*']],
            ['Transparansi APBDes', '/apbdes', ['apbdes*']],
        ]],
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

    <meta name="theme-color" content="#293B30">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    @stack('head')
</head>
<body @class(['has-hero' => $__env->hasSection('hero'), trim($__env->yieldContent('body_class'))])>
    <a class="skip-link" href="#konten">Lewati ke konten</a>

    <header class="kl-header" id="site-header">
        <div class="wide kl-bar">
            <a href="{{ url('/') }}" class="kl-brand" aria-label="{{ $site['site_name'] }} - Beranda">
                @if ($logoUrl)
                    <img class="kl-brand-logo" src="{{ $logoUrl }}" alt="" width="42" height="42">
                @else
                    @include('partials.logo-mark')
                @endif
                <span class="kl-brand-text">
                    <span class="kl-brand-name">{{ $site['site_name'] }}</span>
                    <span class="kl-brand-sub">Portal Informasi Desa</span>
                </span>
            </a>

            <nav class="kl-nav" id="main-nav" aria-label="Menu utama">
                <ul>
                    @foreach ($menu as [$label, $path, $extra])
                        @if ($path === null)
                            <li @class(['has-sub', 'active-group' => collect($extra)->contains(fn ($c) => $isActive($c[2]))])>
                                <button type="button" class="nav-drop" aria-expanded="false" aria-haspopup="true">{{ $label }}<span class="caret" aria-hidden="true"></span></button>
                                <ul class="sub-nav">
                                    @foreach ($extra as [$childLabel, $childPath, $childPatterns])
                                        <li><a href="{{ url($childPath) }}" @class(['active' => $isActive($childPatterns)]) @if ($isActive($childPatterns)) aria-current="page" @endif>{{ $childLabel }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ url($path) }}" @class(['active' => $isActive($extra)]) @if ($isActive($extra)) aria-current="page" @endif>{{ $label }}</a></li>
                        @endif
                    @endforeach
                    <li>
                        @auth
                            <a class="kl-login-m" href="{{ route('admin.dashboard') }}">Dashboard</a>
                        @else
                            <a class="kl-login-m" href="{{ route('admin.login') }}">Login Admin</a>
                        @endauth
                    </li>
                </ul>
            </nav>

            <div class="kl-actions">
                <button type="button" class="kl-icon-btn" id="search-toggle" aria-label="Cari berita" aria-expanded="false" aria-controls="search-panel">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                </button>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="kl-login">Dashboard</a>
                @else
                    <a href="{{ route('admin.login') }}" class="kl-login">Login Admin</a>
                @endauth
                <button type="button" class="kl-icon-btn nav-toggle" id="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="main-nav">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>

        <div class="kl-search" id="search-panel" hidden>
            <div class="wide">
                <form action="{{ url('/search') }}" method="GET" role="search" class="search-form">
                    <label for="q" class="visually-hidden">Cari berita</label>
                    <input type="search" id="q" name="q" placeholder="Cari berita desa..." value="{{ request('q') }}" required minlength="2" maxlength="100">
                    <button type="submit" class="btn">Cari</button>
                </form>
            </div>
        </div>
    </header>

    <main id="konten">
        @hasSection('hero')
            @yield('hero')
        @endif
        @yield('content')
    </main>

    <footer class="kl-footer">
        <div class="wide kl-footer-top">
            <div>
                <p class="kl-footer-word">{{ \Illuminate\Support\Str::title(mb_strtolower($site['site_name'])) }}<em>.</em></p>
                <p>{{ $site['site_tagline'] }}</p>
                @if (!empty($site['address']))
                    <p>{{ $site['address'] }}</p>
                @endif
            </div>

            <div class="kl-footer-cols">
                <div>
                    <h3>Jelajahi</h3>
                    <ul>
                        <li><a href="{{ url('/profil') }}">Profil Desa</a></li>
                        <li><a href="{{ url('/pemerintahan') }}">Pemerintahan</a></li>
                        <li><a href="{{ url('/potensi') }}">Potensi Desa</a></li>
                        <li><a href="{{ url('/berita') }}">Berita</a></li>
                        <li><a href="{{ url('/peta-desa') }}">Peta Desa</a></li>
                        <li><a href="{{ url('/galeri') }}">Galeri</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Layanan</h3>
                    <ul>
                        <li><a href="{{ url('/informasi') }}">Informasi Layanan</a></li>
                        <li><a href="{{ url('/pengumuman') }}">Pengumuman</a></li>
                        <li><a href="{{ url('/agenda') }}">Kalender Warga</a></li>
                        <li><a href="{{ url('/aspirasi') }}">Ruang Aspirasi</a></li>
                        <li><a href="{{ url('/barang-hilang') }}">Barang Hilang &amp; Ditemukan</a></li>
                        <li><a href="{{ url('/apbdes') }}">Transparansi APBDes</a></li>
                    </ul>
                </div>
                <div>
                    <h3>Kontak</h3>
                    @php($hasContact = !empty($site['phone']) || !empty($site['email']) || !empty($site['services_hours']))
                    @if ($hasContact)
                        <ul>
                            @if (!empty($site['phone']))<li>Telepon: {{ $site['phone'] }}</li>@endif
                            @if (!empty($site['email']))<li><a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a></li>@endif
                            @if (!empty($site['services_hours']))<li>Jam pelayanan: {{ $site['services_hours'] }}</li>@endif
                        </ul>
                    @else
                        <p class="muted">Informasi kontak belum diisi pengelola.</p>
                    @endif
                    @if (!empty($site['facebook']) || !empty($site['instagram']) || !empty($site['youtube']))
                        <ul>
                            @if (!empty($site['facebook']))<li><a href="{{ $site['facebook'] }}" rel="noopener" target="_blank">Facebook</a></li>@endif
                            @if (!empty($site['instagram']))<li><a href="{{ $site['instagram'] }}" rel="noopener" target="_blank">Instagram</a></li>@endif
                            @if (!empty($site['youtube']))<li><a href="{{ $site['youtube'] }}" rel="noopener" target="_blank">YouTube</a></li>@endif
                        </ul>
                    @endif
                </div>
            </div>
        </div>

        <div class="kl-footer-bottom">
            <div class="wide">&copy; {{ date('Y') }} {{ $site['site_name'] }}. Hak cipta dilindungi.</div>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}" defer></script>
    @stack('scripts')
</body>
</html>
