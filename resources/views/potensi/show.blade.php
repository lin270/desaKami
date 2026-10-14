@extends('layouts.app')

@section('title', $potential->title)
@section('description', $potential->summary_text)
@section('og_type', 'article')
@if ($potential->image)
    @section('image', $potential->image_url)
@endif

@section('content')
    <div class="container artikel-layout">
        <article class="artikel" style="max-width:820px">
            @if ($preview)
                <p class="preview-banner" role="status">Pratinjau: data ini belum ditayangkan dan hanya terlihat oleh admin.</p>
            @endif

            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/potensi') }}">Potensi</a></li>
                    <li><a href="{{ url('/potensi') }}?kategori={{ $potential->category }}">{{ $potential->category_label }}</a></li>
                </ol>
            </nav>

            <a class="kategori" href="{{ url('/potensi') }}?kategori={{ $potential->category }}">{{ $potential->category_label }}</a>
            <h1 class="artikel-judul">{{ $potential->title }}</h1>

            @if ($potential->image)
                <figure class="artikel-gambar">
                    <img src="{{ $potential->image_url }}" alt="{{ $potential->title }}" width="1200" height="675" fetchpriority="high">
                </figure>
            @endif

            @if ($potential->location || $potential->contact)
                <dl class="info-box">
                    @if ($potential->location)
                        <div><dt>Lokasi</dt><dd>{{ $potential->location }}</dd></div>
                    @endif
                    @if ($potential->contact)
                        <div><dt>Kontak</dt><dd>{{ $potential->contact }}</dd></div>
                    @endif
                </dl>
            @endif

            <div class="prose">{!! $body !!}</div>

            @if ($related->isNotEmpty())
                <section class="terkait" aria-label="Potensi terkait">
                    <x-section-title title="Potensi Lainnya" :href="url('/potensi').'?kategori='.$potential->category" />
                    <div class="grid-news grid-3">
                        @foreach ($related as $r)
                            <article class="card">
                                <a href="{{ $r->url }}" class="card-img" tabindex="-1" aria-hidden="true">
                                    <img src="{{ $r->image_url }}" alt="" width="600" height="375" loading="lazy">
                                </a>
                                <div class="card-body">
                                    <h3 class="card-title"><a href="{{ $r->url }}">{{ $r->title }}</a></h3>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </article>
    </div>
@endsection
