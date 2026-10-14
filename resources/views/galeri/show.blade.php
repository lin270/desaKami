@extends('layouts.app')

@section('title', $gallery->title)
@section('description', $gallery->description ?: 'Album foto '.$gallery->title.' - Desa Klepu.')
@section('image', $gallery->cover_url)

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/galeri') }}">Galeri</a></li>
                    <li aria-current="page">{{ $gallery->title }}</li>
                </ol>
            </nav>
            <h1>{{ $gallery->title }}</h1>
            <p class="meta" style="margin-bottom:.5rem">
                @if ($gallery->taken_at)<time datetime="{{ $gallery->taken_at->toDateString() }}">{{ $gallery->taken_at->translatedFormat('j F Y') }}</time>@endif
                <span>{{ $gallery->images->count() }} foto</span>
            </p>
            @if ($gallery->description)
                <p class="lead">{{ $gallery->description }}</p>
            @endif
        </div>
    </div>

    <section class="section">
        <div class="container">
            @if ($gallery->images->isNotEmpty())
                <div class="masonry">
                    @foreach ($gallery->images as $img)
                        <a href="{{ $img->image_url }}" data-lightbox data-caption="{{ $img->caption }}">
                            <img src="{{ $img->image_url }}" alt="{{ $img->caption ?: $gallery->title }}" width="600" height="400" loading="lazy">
                        </a>
                    @endforeach
                </div>
            @else
                <p class="kosong">Album ini belum berisi foto.</p>
            @endif

            <p style="margin-top:2rem"><a class="btn" href="{{ url('/galeri') }}">&larr; Semua album</a></p>
        </div>
    </section>

    <dialog class="lightbox" id="lightbox" aria-label="Penampil foto">
        <div class="lb-inner">
            <button type="button" class="lb-btn lb-close" aria-label="Tutup">&times;</button>
            <button type="button" class="lb-btn lb-prev" aria-label="Foto sebelumnya">&lsaquo;</button>
            <img src="" alt="">
            <p class="lb-caption"></p>
            <button type="button" class="lb-btn lb-next" aria-label="Foto berikutnya">&rsaquo;</button>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script src="{{ asset('js/galeri.js') }}?v={{ filemtime(public_path('js/galeri.js')) }}" defer></script>
@endpush
