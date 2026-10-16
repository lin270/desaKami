<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#293B30">
    <title>Login Admin - {{ $site['site_name'] }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}?v={{ filemtime(public_path('css/auth.css')) }}">
</head>
<body>
    <div class="auth">
        <aside class="auth-art" aria-hidden="true">
            <div class="scene">@include('partials.hero-landscape')</div>
            <div class="auth-art-inner">
                @include('partials.logo-mark')
                <h2>{{ $site['site_name'] }}</h2>
                <p>Ruang pengelolaan berita, pengumuman, agenda, dan data desa.</p>
            </div>
        </aside>

        <main class="auth-panel">
            <div class="auth-card">
                <p class="auth-eyebrow">Panel Admin</p>
                <h1>Masuk</h1>
                <p class="sub">Gunakan akun pengelola yang terdaftar.</p>

                @if ($errors->any())
                    <div class="error" role="alert">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.store') }}">
                    @csrf

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">

                    <label for="password">Password</label>
                    <div class="field">
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <button type="button" class="pw-toggle" id="pw-toggle" aria-pressed="false" aria-controls="password" hidden>Tampilkan</button>
                    </div>

                    <label class="ingat">
                        <input type="checkbox" name="remember" value="1"> Ingat saya
                    </label>

                    <button type="submit" class="submit">Masuk</button>
                </form>

                <a class="kembali" href="{{ route('home') }}">&larr; Kembali ke website</a>
            </div>
        </main>
    </div>
    <script src="{{ asset('js/auth.js') }}?v={{ filemtime(public_path('js/auth.js')) }}" defer></script>
</body>
</html>
