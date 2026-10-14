@php
    $authUser = auth()->user();
    $menu = [
        ['Dashboard', 'admin.dashboard', 'admin.dashboard', true],
        ['Berita', 'admin.posts.index', 'admin.posts.*', true],
        ['Kategori', 'admin.categories.index', 'admin.categories.*', $authUser->isAdmin()],
        ['Pengumuman', 'admin.announcements.index', 'admin.announcements.*', true],
        ['Kalender Warga', 'admin.events.index', 'admin.events.*', true],
        ['Lokasi Peta Desa', 'admin.village-locations.index', 'admin.village-locations.*', $authUser->isAdmin()],
        ['Galeri', 'admin.galleries.index', 'admin.galleries.*', true],
        ['Potensi Desa', 'admin.potentials.index', 'admin.potentials.*', true],
        ['Barang Hilang', 'admin.lost-items.index', 'admin.lost-items.*', true],
        ['APBDes', 'admin.budgets.index', 'admin.budgets.*', $authUser->isAdmin()],
        ['Pemerintahan Desa', 'admin.officials.index', 'admin.officials.*', $authUser->isAdmin()],
        ['Profil Desa', 'admin.profile.edit', 'admin.profile.*', $authUser->isAdmin()],
        ['Data Desa', 'admin.statistics.index', 'admin.statistics.*', $authUser->isAdmin()],
        ['Pengguna', 'admin.users.index', 'admin.users.*', $authUser->isAdmin()],
        ['Pengaturan', 'admin.settings.edit', 'admin.settings.*', $authUser->isAdmin()],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') - Admin {{ $site['site_name'] }}</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body>
    <div class="shell">
        <aside class="side" id="side" aria-label="Menu admin">
            <div class="side-brand">
                <span class="side-logo" aria-hidden="true">M</span>
                <span>
                    <strong>{{ $site['site_name'] }}</strong>
                    <small>Panel Admin</small>
                </span>
            </div>

            <nav>
                <ul>
                    @foreach ($menu as [$label, $routeName, $pattern, $show])
                        @if ($show)
                            <li>
                                <a href="{{ route($routeName) }}" @class(['active' => request()->routeIs(...(array) $pattern)])>{{ $label }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <a class="side-site" href="{{ url('/') }}" target="_blank" rel="noopener">Lihat website &nearr;</a>
        </aside>
        <div class="side-overlay" id="side-overlay"></div>

        <div class="main">
            <header class="top">
                <button type="button" class="side-toggle" id="side-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="side">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="top-title">@yield('title', 'Admin')</span>
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
                    <div class="alert alert-ok" role="status">{{ session('success') }}</div>
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
