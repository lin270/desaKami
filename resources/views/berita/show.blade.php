@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->excerpt_text)
@section('og_type', 'article')
@section('image', $post->thumbnail_url)

@push('head')
    {!! \App\Support\JsonLd::article($post, $site, $logoUrl) !!}
    @if ($post->published_at)
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}">
    @endif
    <meta property="article:section" content="{{ $post->category->name }}">
@endpush

@section('content')
    <div class="container layout artikel-layout">
        <article class="artikel">
            @if ($preview)
                <p class="preview-banner" role="status">Pratinjau: berita ini belum terbit dan hanya terlihat oleh admin.</p>
            @endif

            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/berita') }}">Berita</a></li>
                    <li><a href="{{ $post->category->url }}">{{ $post->category->name }}</a></li>
                </ol>
            </nav>

            <a class="kategori" href="{{ $post->category->url }}">{{ $post->category->name }}</a>
            <h1 class="artikel-judul">{{ $post->title }}</h1>

            <p class="meta">
                @if ($post->published_at)
                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->translatedFormat('l, j F Y') }}</time>
                @else
                    <span>Draft</span>
                @endif
                <span>Oleh {{ $post->author->name }}</span>
                <span>{{ number_format($post->views, 0, ',', '.') }} dibaca</span>
            </p>

            <figure class="artikel-gambar">
                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" width="1200" height="675" fetchpriority="high">
            </figure>

            <div class="prose">
                {!! $body !!}
            </div>

            @if ($related->isNotEmpty())
                <section class="terkait" aria-label="Berita terkait">
                    <x-section-title title="Berita Terkait" />
                    <div class="grid-news">
                        @foreach ($related as $item)
                            <x-post-card :post="$item" />
                        @endforeach
                    </div>
                </section>
            @endif
        </article>

        <x-sidebar :show-latest="true" />
    </div>
@endsection
