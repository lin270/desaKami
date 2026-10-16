<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>{{ $judul }} - Desa Klepu</title>
    <style>
        body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1.5rem;
               background:#F5F1E8; color:#292B27; font-family:system-ui,-apple-system,"Segoe UI",Roboto,sans-serif; text-align:center; }
        .kode { font:500 clamp(4rem,18vw,7rem)/1 "Iowan Old Style","Palatino Linotype",Georgia,serif; color:#87907B; margin:0 0 .5rem; }
        h1 { font-family:"Iowan Old Style","Palatino Linotype",Georgia,serif;font-weight:500; color:#293B30; margin:0 0 .75rem; }
        p { margin:0 0 1.25rem; color:#5b6354; max-width:34rem; }
        a { display:inline-block; padding:.55rem 1.1rem; background:#293B30; color:#fff; border-radius:2px; font-weight:600; text-decoration:none; }
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
