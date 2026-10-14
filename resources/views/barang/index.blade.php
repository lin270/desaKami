@extends('layouts.app')

@section('title', 'Barang Hilang & Ditemukan')
@section('description', 'Informasi barang hilang dan barang ditemukan di wilayah Desa Klepu.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Barang Hilang &amp; Ditemukan</li>
                </ol>
            </nav>
            <h1>Barang Hilang &amp; Ditemukan</h1>
            <p class="lead">Kehilangan atau menemukan barang di wilayah desa? Lihat laporan di sini atau hubungi kantor desa.</p>
        </div>
    </div>

    <section class="section">
        <div class="container layout">
            <div>
                <div class="chips" aria-label="Filter jenis">
                    <a href="{{ url('/barang-hilang') }}" @class(['chip', 'active' => ! $type])>Semua</a>
                    @foreach ($types as $key => $label)
                        <a href="{{ url('/barang-hilang') }}?jenis={{ $key }}" @class(['chip', 'active' => $type === $key])>
                            {{ $label }}@if (($counts[$key] ?? 0) > 0) ({{ $counts[$key] }})@endif
                        </a>
                    @endforeach
                </div>

                @if ($items->isNotEmpty())
                    <div class="barang-list">
                        @foreach ($items as $it)
                            <article @class(['barang', 'selesai' => $it->is_resolved])>
                                <a href="{{ $it->url }}" class="barang-foto" tabindex="-1" aria-hidden="true">
                                    @if ($it->image)
                                        <img src="{{ $it->image_url }}" alt="" width="200" height="150" loading="lazy">
                                    @else
                                        <span class="barang-ikon">{{ $it->type === 'hilang' ? '?' : '!' }}</span>
                                    @endif
                                </a>
                                <div class="barang-isi">
                                    <p class="meta">
                                        <span @class(['badge', 'badge-hilang' => $it->type === 'hilang', 'badge-ditemukan' => $it->type === 'ditemukan'])>{{ $it->type_label }}</span>
                                        @if ($it->is_resolved)<span class="badge badge-selesai">Selesai</span>@endif
                                        <time datetime="{{ $it->event_date->toDateString() }}">{{ $it->event_date->translatedFormat('j F Y') }}</time>
                                    </p>
                                    <h2><a href="{{ $it->url }}">{{ $it->title }}</a></h2>
                                    <p class="meta"><span>{{ $it->location }}</span></p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{ $items->links() }}
                @else
                    <p class="kosong">Belum ada laporan{{ $type ? ' pada jenis ini' : '' }}.</p>
                @endif
            </div>

            <aside class="sidebar" aria-label="Cara melapor">
                <section class="widget">
                    <h2 class="widget-title">Cara Melapor</h2>
                    <p>Untuk melaporkan barang hilang atau ditemukan, datang atau hubungi kantor desa. Petugas akan mengumumkannya di halaman ini.</p>
                    <ul class="daftar-biasa">
                        @if (!empty($site['phone']))<li>Telepon: {{ $site['phone'] }}</li>@endif
                        @if (!empty($site['services_hours']))<li>Jam pelayanan: {{ $site['services_hours'] }}</li>@endif
                        @if (!empty($site['address']))<li>{{ $site['address'] }}</li>@endif
                    </ul>
                </section>
            </aside>
        </div>
    </section>
@endsection
