@extends('layouts.app')

@section('title', 'Profil Desa')
@section('description', 'Profil Desa Klepu: tentang desa, sejarah, visi dan misi, wilayah, serta data demografi.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Profil</li>
                </ol>
            </nav>
            <h1>Profil Desa Klepu</h1>
            <p class="lead">Mengenal desa, sejarah, visi dan misi, serta data wilayah.</p>
        </div>
    </div>

    <div class="container halaman">
        <nav class="toc" aria-label="Isi halaman">
            <ul>
                <li><a href="#tentang">Tentang Desa</a></li>
                <li><a href="#sejarah">Sejarah</a></li>
                <li><a href="#visi-misi">Visi &amp; Misi</a></li>
                <li><a href="#pemerintahan">Struktur Pemerintahan</a></li>
                <li><a href="#wilayah">Wilayah</a></li>
                <li><a href="#demografi">Demografi</a></li>
                <li><a href="#informasi-umum">Informasi Umum</a></li>
            </ul>
        </nav>

        @if ($profile->hero_image)
            <figure class="artikel-gambar" style="margin-top:1.5rem">
                <img src="{{ $profile->hero_image_url }}" alt="Suasana Desa Klepu" width="1200" height="675">
            </figure>
        @endif

        <section class="blok" id="tentang">
            <h2>Tentang Desa Klepu</h2>
            <div class="prose">{!! $about ?: '<p>Belum diisi.</p>' !!}</div>
        </section>

        <section class="blok" id="sejarah">
            <h2>Sejarah Desa</h2>
            <div class="prose">{!! $history ?: '<p>Belum diisi.</p>' !!}</div>
        </section>

        <section class="blok" id="visi-misi">
            <h2>Visi dan Misi</h2>
            @if ($profile->vision)
                <p class="visi">{{ $profile->vision }}</p>
            @endif
            @if (count($missions))
                <h3 class="sub">Misi</h3>
                <ol class="misi">
                    @foreach ($missions as $m)
                        <li>{{ $m }}</li>
                    @endforeach
                </ol>
            @endif
        </section>

        <section class="blok" id="pemerintahan">
            <h2>Struktur Pemerintahan</h2>
            @if ($leaders->isNotEmpty())
                <div class="pejabat-grid pejabat-ringkas">
                    @foreach ($leaders as $o)
                        @include('pemerintahan._kartu', ['o' => $o])
                    @endforeach
                </div>
            @endif
            <p><a class="btn" href="{{ url('/pemerintahan') }}">Lihat struktur lengkap</a></p>
        </section>

        <section class="blok" id="wilayah">
            <h2>Wilayah Desa</h2>
            @if ($profile->area_description)
                <p>{{ $profile->area_description }}</p>
            @endif
            @if ($areaStats->isNotEmpty())
                <div class="stats">
                    @foreach ($areaStats as $s)
                        <div class="stat">
                            <span class="stat-nilai">{{ $s->value }}</span>
                            @if ($s->unit)<span class="stat-unit">{{ $s->unit }}</span>@endif
                            <span class="stat-label">{{ $s->label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <section class="blok" id="demografi">
            <h2>Data Demografi</h2>
            @if ($populationStats->isNotEmpty())
                <div class="stats">
                    @foreach ($populationStats as $s)
                        <div class="stat">
                            <span class="stat-nilai">{{ $s->value }}</span>
                            @if ($s->unit)<span class="stat-unit">{{ $s->unit }}</span>@endif
                            <span class="stat-label">{{ $s->label }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="kosong">Data demografi belum tersedia.</p>
            @endif
        </section>

        <section class="blok" id="informasi-umum">
            <h2>Informasi Umum</h2>
            <div class="prose">{!! $generalInfo ?: '<p>Belum diisi.</p>' !!}</div>
        </section>
    </div>
@endsection
