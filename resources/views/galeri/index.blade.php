@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Galeri foto kegiatan Desa Klepu.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Galeri</li>
                </ol>
            </nav>
            <h1>Galeri Kegiatan</h1>
            <p class="lead">Dokumentasi foto kegiatan Desa Klepu.</p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            @if ($galleries->isNotEmpty())
                <div class="album-grid">
                    @foreach ($galleries as $g)
                        <article class="album">
                            <a href="{{ $g->url }}" class="album-cover" tabindex="-1" aria-hidden="true">
                                <img src="{{ $g->cover_url }}" alt="" width="600" height="450" loading="lazy">
                            </a>
                            <div class="album-body">
                                <h2><a href="{{ $g->url }}">{{ $g->title }}</a></h2>
                                <p class="meta">
                                    @if ($g->taken_at)<time datetime="{{ $g->taken_at->toDateString() }}">{{ $g->taken_at->translatedFormat('j F Y') }}</time>@endif
                                    <span>{{ $g->images_count }} foto</span>
                                </p>
                                @if ($g->description)
                                    <p class="album-desc">{{ $g->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $galleries->links() }}
            @else
                <p class="kosong">Belum ada album foto.</p>
            @endif
        </div>
    </section>
@endsection
