@extends('layouts.app')

@section('title', $gallery->title)
@section('description', $gallery->description ?: 'Album foto '.$gallery->title.' - '.$site['site_name'].'.')
@section('image', $gallery->cover_url)

@section('content')
    <header class="alb-cover">
        <div class="wide">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/galeri') }}">Galeri</a></li>
                    <li aria-current="page">{{ $gallery->title }}</li>
                </ol>
            </nav>
            <div class="alb-cover-frame">
                <p class="alb-cover-kicker">Album foto &middot; {{ $site['site_name'] }}</p>
                <h1>{{ $gallery->title }}</h1>
                <p class="alb-cover-meta">
                    @if ($gallery->taken_at)<time datetime="{{ $gallery->taken_at->toDateString() }}">{{ $gallery->taken_at->translatedFormat('j F Y') }}</time> &middot; @endif
                    {{ $gallery->images->count() }} foto
                </p>
                @if ($gallery->description)<p class="alb-cover-desc">{{ $gallery->description }}</p>@endif
            </div>
        </div>
    </header>

    <section class="alb-paper alb-pages">
        <div class="wide">
            @if ($gallery->images->isNotEmpty())
                <div class="pola-wall">
                    @foreach ($gallery->images as $img)
                        <a class="pola" href="{{ $img->image_url }}" data-lightbox data-caption="{{ $img->caption }}">
                            <span class="pola-tape" aria-hidden="true"></span>
                            <img src="{{ $img->image_url }}" alt="{{ $img->caption ?: $gallery->title }}" width="600" height="450" loading="lazy">
                            <span class="pola-cap">{{ $img->caption ?: 'Foto '.$loop->iteration }}</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="kl-empty"><strong>Album ini belum berisi foto</strong></div>
            @endif

            <p class="alb-back"><a class="link-arrow" href="{{ url('/galeri') }}">Kembali ke semua album</a></p>
        </div>
    </section>

    <dialog class="lightbox" id="lightbox" aria-label="Penampil foto">
        <div class="lb-inner">
            <button type="button" class="lb-btn lb-close" aria-label="Tutup">&times;</button>
            <button type="button" class="lb-btn lb-prev" aria-label="Foto sebelumnya">&lsaquo;</button>
            <img src="" alt="">
            <p class="lb-caption"></p>
            <p class="lb-count" aria-live="polite"></p>
            <button type="button" class="lb-btn lb-next" aria-label="Foto berikutnya">&rsaquo;</button>
        </div>
    </dialog>
@endsection

@push('scripts')
    <script src="{{ asset('js/galeri.js') }}?v={{ filemtime(public_path('js/galeri.js')) }}" defer></script>
@endpush
