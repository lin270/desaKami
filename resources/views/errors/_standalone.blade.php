<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $judul }} - Desa Klepu</title>
    <style>
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem;
               background:#faf8f3; color:#1b1f1d; font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; text-align:center; }
        .kode { font:700 clamp(4rem,18vw,7rem)/1 Georgia,serif; color:#c8962e; margin:0 0 .5rem; }
        h1 { font-family:Georgia,serif; color:#1f5d3a; margin:0 0 .75rem; }
        p { margin:0 0 1.25rem; color:#5f6762; max-width:34rem; }
        a { display:inline-block; padding:.55rem 1.1rem; background:#1f5d3a; color:#fff; border-radius:6px; font-weight:600; text-decoration:none; }
    </style>
</head>
<body>
    <main>
        <p class="kode">{{ $kode }}</p>
        <h1>{{ $judul }}</h1>
        <p>{{ $pesan }}</p>
        <a href="/">Kembali ke Beranda</a>
    </main>
</body>
</html>
