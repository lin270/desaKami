@php
    $authUser = auth()->user();
    $isAdmin = $authUser->isAdmin();
    // [judul grup => [[label, route, pola-aktif, tampil], ...]]
    $groups = [
        'Konten' => [
            ['Dashboard', 'admin.dashboard', 'admin.dashboard', true],
            ['Berita', 'admin.posts.index', 'admin.posts.*', true],
            ['Kategori', 'admin.categories.index', 'admin.categories.*', $isAdmin],
            ['Pengumuman', 'admin.announcements.index', 'admin.announcements.*', true],
            ['Kalender Warga', 'admin.events.index', 'admin.events.*', true],
            ['Galeri', 'admin.galleries.index', 'admin.galleries.*', true],
            ['Potensi Desa', 'admin.potentials.index', 'admin.potentials.*', true],
            ['Aspirasi Warga', 'admin.aspirations.index', 'admin.aspirations.*', true],
            ['Barang Hilang', 'admin.lost-items.index', 'admin.lost-items.*', true],
        ],
        'Data Desa' => [
            ['Lokasi Peta Desa', 'admin.village-locations.index', 'admin.village-locations.*', $isAdmin],
            ['APBDes', 'admin.budgets.index', 'admin.budgets.*', $isAdmin],
            ['Pemerintahan Desa', 'admin.officials.index', 'admin.officials.*', $isAdmin],
            ['Profil Desa', 'admin.profile.edit', 'admin.profile.*', $isAdmin],
            ['Data Desa', 'admin.statistics.index', 'admin.statistics.*', $isAdmin],
        ],
        'Sistem' => [
            ['Pengguna', 'admin.users.index', 'admin.users.*', $isAdmin],
            ['Pengaturan', 'admin.settings.edit', 'admin.settings.*', $isAdmin],
        ],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - Admin {{ $site['site_name'] }}</title>
    <meta name="theme-color" content="#293B30">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
    <div class="shell">
        <aside class="side" id="side" aria-label="Menu admin">
            <div class="side-brand">
                @include('partials.logo-mark')
                <span class="side-brand-text">
                    <strong>{{ $site['site_name'] }}</strong>
                    <small>Panel Admin</small>
                </span>
            </div>

            <nav>
                @foreach ($groups as $groupLabel => $links)
                    @php($visible = collect($links)->filter(fn ($l) => $l[3]))
                    @if ($visible->isNotEmpty())
                        <p class="side-group">{{ $groupLabel }}</p>
                        <ul>
                            @foreach ($visible as [$label, $routeName, $pattern])
                                @php($active = request()->routeIs(...(array) $pattern))
                                <li>
                                    <a href="{{ route($routeName) }}" @class(['active' => $active]) @if ($active) aria-current="page" @endif title="{{ $label }}">
                                        <span class="side-dot" aria-hidden="true"></span><span class="side-label">{{ $label }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </nav>

            <a class="side-site" href="{{ url('/') }}" target="_blank" rel="noopener"><span class="side-label">Lihat website &nearr;</span></a>
        </aside>
        <div class="side-overlay" id="side-overlay"></div>

        <div class="main">
            <header class="top">
                <button type="button" class="side-toggle" id="side-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="side">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <button type="button" class="side-collapse" id="side-collapse" aria-label="Ciutkan menu samping" aria-pressed="false">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>
                </button>
                <div class="top-title">
                    <nav class="crumb" aria-label="Breadcrumb"><a href="{{ route('admin.dashboard') }}">Admin</a><span aria-hidden="true">/</span><span>@yield('title', 'Admin')</span></nav>
                </div>
                <div class="top-user">
                    <span class="top-name">{{ $authUser->name }} <em>{{ $authUser->role }}</em></span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
                    </form>
                </div>
            </header>

            <main class="content">
                @if (session('success'))
                    <div class="alert alert-ok" role="status" data-dismiss="6000">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-err" role="alert">{{ session('error') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-err" role="alert">
                        <strong>Data belum dapat disimpan.</strong> Periksa isian yang ditandai di bawah.
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}" defer></script>
</body>
</html>
