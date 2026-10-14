<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin - Desa Klepu</title>
    <style>
        :root { --hijau:#1f5d3a; --emas:#c8962e; --krem:#faf8f3; --teks:#1b1f1d; --abu:#6b726e; --garis:#dcd8cc; --merah:#b3261e; }
        * { box-sizing: border-box; }
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1rem;
               background:var(--krem); color:var(--teks); font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; }
        .kartu { width:100%; max-width:380px; background:#fff; border:1px solid var(--garis); border-radius:8px; padding:2rem 1.75rem; }
        .merek { text-align:center; margin-bottom:1.5rem; }
        .merek h1 { margin:0; font-family:Georgia,"Times New Roman",serif; font-size:1.4rem; letter-spacing:.04em; color:var(--hijau); }
        .merek p { margin:.25rem 0 0; font-size:.875rem; color:var(--abu); }
        .garis { width:48px; height:3px; background:var(--emas); margin:.75rem auto 0; border:0; }
        label { display:block; font-size:.875rem; font-weight:600; margin:1rem 0 .35rem; }
        input[type=email], input[type=password] { width:100%; padding:.65rem .75rem; border:1px solid var(--garis); border-radius:6px; font-size:1rem; }
        input:focus { outline:2px solid var(--hijau); outline-offset:1px; }
        .ingat { display:flex; align-items:center; gap:.5rem; margin:1rem 0 1.25rem; font-size:.875rem; font-weight:400; }
        button { width:100%; padding:.7rem; background:var(--hijau); color:#fff; border:0; border-radius:6px; font-size:1rem; font-weight:600; cursor:pointer; }
        button:hover { background:#174a2e; }
        .error { background:#fdecea; border:1px solid #f3b8b3; color:var(--merah); padding:.6rem .75rem; border-radius:6px; font-size:.875rem; margin-bottom:.5rem; }
        .kembali { display:block; text-align:center; margin-top:1.25rem; font-size:.875rem; color:var(--abu); text-decoration:none; }
        .kembali:hover { color:var(--hijau); }
    </style>
</head>
<body>
    <main class="kartu">
        <div class="merek">
            <h1>DESA KLEPU</h1>
            <p>Login Admin Website Desa</p>
            <hr class="garis">
        </div>

        @if ($errors->any())
            <div class="error" role="alert">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <label class="ingat">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>

            <button type="submit">Masuk</button>
        </form>

        <a class="kembali" href="{{ route('home') }}">&larr; Kembali ke website</a>
    </main>
</body>
</html>
