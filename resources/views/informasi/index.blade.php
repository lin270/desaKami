@extends('layouts.app')

@section('title', 'Informasi Desa')
@section('description', 'Informasi pelayanan Desa Klepu: persyaratan administrasi, jam pelayanan, kontak, dan informasi publik.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Informasi</li>
                </ol>
            </nav>
            <h1>Informasi untuk Masyarakat</h1>
            <p class="lead">Pelayanan, persyaratan administrasi, dan kontak Desa Klepu.</p>
        </div>
    </div>

    <div class="container halaman">
        <section class="blok" id="pelayanan">
            <h2>Jam Pelayanan dan Kontak</h2>
            <div class="kontak-grid">
                <div class="kontak-item">
                    <h3>Jam Pelayanan</h3>
                    <p>{{ $site['services_hours'] ?? 'Hubungi kantor desa untuk jam pelayanan.' }}</p>
                </div>
                <div class="kontak-item">
                    <h3>Alamat Kantor</h3>
                    <p>{{ $site['address'] ?? 'Alamat belum diisi.' }}</p>
                </div>
                <div class="kontak-item">
                    <h3>Telepon</h3>
                    <p>{{ $site['phone'] ?? '-' }}</p>
                </div>
                <div class="kontak-item">
                    <h3>Email</h3>
                    <p>
                        @if (!empty($site['email']))
                            <a href="mailto:{{ $site['email'] }}">{{ $site['email'] }}</a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>
        </section>

        <section class="blok" id="persyaratan">
            <h2>Persyaratan Administrasi</h2>
            <p class="muted">Contoh persyaratan umum. Persyaratan dapat berbeda; pastikan ke kantor desa sebelum mengurus.</p>
            @foreach ($services as [$nama, $syarat])
                <details class="akordeon">
                    <summary>{{ $nama }}</summary>
                    <div class="akordeon-isi">
                        <ul>
                            @foreach ($syarat as $s)
                                <li>{{ $s }}</li>
                            @endforeach
                        </ul>
                    </div>
                </details>
            @endforeach
        </section>

        <section class="blok" id="informasi-publik">
            <h2>Informasi Publik</h2>
            <ul class="daftar-biasa">
                @foreach ($publicInfo as $info)
                    <li>{{ $info }}</li>
                @endforeach
            </ul>
        </section>

        <section class="blok" id="pengumuman">
            <x-section-title title="Pengumuman Terbaru" :href="url('/pengumuman')" />
            @forelse ($announcements as $a)
                <article class="pengumuman">
                    <p class="meta">
                        @if ($a->is_important)<span class="badge badge-penting">Penting</span>@endif
                        <span class="badge">{{ $a->category }}</span>
                        <time datetime="{{ $a->published_at->toDateString() }}">{{ $a->published_at->translatedFormat('j F Y') }}</time>
                    </p>
                    <h3><a href="{{ $a->url }}">{{ $a->title }}</a></h3>
                </article>
            @empty
                <p class="kosong">Belum ada pengumuman.</p>
            @endforelse
        </section>
    </div>
@endsection
