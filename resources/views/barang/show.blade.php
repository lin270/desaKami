@extends('layouts.app')

@section('title', $item->type_label.': '.$item->title)
@section('description', \Illuminate\Support\Str::limit(strip_tags($item->description), 160))
@if ($item->image)
    @section('image', $item->image_url)
@endif

@section('content')
    <div class="container layout artikel-layout">
        <article class="artikel">
            @if ($preview)
                <p class="preview-banner" role="status">Pratinjau: laporan ini belum ditayangkan dan hanya terlihat oleh admin.</p>
            @endif

            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/barang-hilang') }}">Barang Hilang &amp; Ditemukan</a></li>
                    <li aria-current="page">{{ $item->type_label }}</li>
                </ol>
            </nav>

            <p class="meta" style="margin-bottom:.5rem">
                <span @class(['badge', 'badge-hilang' => $item->type === 'hilang', 'badge-ditemukan' => $item->type === 'ditemukan'])>{{ $item->type_label }}</span>
                @if ($item->is_resolved)<span class="badge badge-selesai">Selesai</span>@endif
            </p>
            <h1 class="artikel-judul">{{ $item->title }}</h1>

            @if ($item->is_resolved)
                <p class="alert-selesai" role="status">
                    {{ $item->type === 'hilang' ? 'Barang ini sudah ditemukan / dikembalikan.' : 'Barang ini sudah diambil oleh pemiliknya.' }}
                    @if ($item->resolved_at) ({{ $item->resolved_at->translatedFormat('j F Y') }})@endif
                </p>
            @endif

            @if ($item->image)
                <figure class="artikel-gambar">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" width="1200" height="675" fetchpriority="high">
                </figure>
            @endif

            <dl class="info-box">
                <div><dt>{{ $item->type === 'hilang' ? 'Hilang pada' : 'Ditemukan pada' }}</dt><dd>{{ $item->event_date->translatedFormat('l, j F Y') }}</dd></div>
                <div><dt>Lokasi</dt><dd>{{ $item->location }}</dd></div>
                <div><dt>Hubungi</dt><dd>{{ $item->contact ?: 'Kantor Desa'.(!empty($site['phone']) ? ' - '.$site['phone'] : '') }}</dd></div>
            </dl>

            <div class="prose">
                @foreach (preg_split('/\R{2,}/', trim($item->description)) as $para)
                    <p>{!! nl2br(e($para)) !!}</p>
                @endforeach
            </div>

            <p style="margin-top:2rem"><a class="btn" href="{{ url('/barang-hilang') }}">&larr; Semua laporan</a></p>
        </article>

        <aside class="sidebar" aria-label="Laporan lainnya">
            <section class="widget">
                <h2 class="widget-title">Laporan Lainnya</h2>
                @if ($others->isNotEmpty())
                    <ul class="daftar-link">
                        @foreach ($others as $o)
                            <li>
                                <a href="{{ $o->url }}">{{ $o->title }}</a>
                                <span class="meta">{{ $o->type_label }} &middot; {{ $o->event_date->translatedFormat('j F Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="kosong">Tidak ada laporan lain.</p>
                @endif
            </section>
        </aside>
    </div>
@endsection
