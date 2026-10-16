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

@php
    $readTime = max(1, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 200));
    $shareUrl = $post->url;
@endphp

@section('content')
    <div class="wide nw-article-wrap">
        <article class="nw-article">
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

            <header class="nw-art-head">
                <a class="nw-kicker" href="{{ $post->category->url }}">{{ $post->category->name }}</a>
                <h1>{{ $post->title }}</h1>
                @if (filled($post->excerpt_text))
                    <p class="nw-art-deck">{{ \Illuminate\Support\Str::limit($post->excerpt_text, 240) }}</p>
                @endif

                <div class="nw-art-meta">
                    <div class="nw-art-by">
                        <span class="nw-avatar" aria-hidden="true">{{ mb_strtoupper(mb_substr($post->author->name, 0, 1)) }}</span>
                        <div>
                            <b>{{ $post->author->name }}</b>
                            <span>
                                @if ($post->published_at)
                                    <time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->translatedFormat('l, j F Y') }}</time>
                                @else
                                    Draft
                                @endif
                                &middot; {{ $readTime }} menit baca &middot; {{ number_format($post->views, 0, ',', '.') }} dibaca
                            </span>
                        </div>
                    </div>
                    <div class="nw-share" aria-label="Bagikan berita">
                        <span>Bagikan</span>
                        <a href="https://wa.me/?text={{ urlencode($post->title.' '.$shareUrl) }}" target="_blank" rel="noopener">WhatsApp</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener">Facebook</a>
                        <button type="button" data-copy="{{ $shareUrl }}">Salin tautan</button>
                    </div>
                </div>
            </header>

            <figure class="nw-art-photo">
                <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" width="1200" height="675" fetchpriority="high">
            </figure>

            <div class="prose nw-prose">
                {!! $body !!}
            </div>

            <footer class="nw-art-foot">
                <a class="nw-kicker" href="{{ $post->category->url }}">Kategori: {{ $post->category->name }}</a>
                <a class="link-arrow" href="{{ url('/berita') }}">Kembali ke semua berita</a>
            </footer>

            @if ($related->isNotEmpty())
                <section class="nw-related" aria-label="Baca juga">
                    <h2>Baca juga</h2>
                    @foreach ($related as $item)
                        <article class="nw-row nw-row-compact">
                            <div class="nw-row-text">
                                <a class="nw-kicker" href="{{ $item->category->url }}">{{ $item->category->name }}</a>
                                <h3><a href="{{ $item->url }}">{{ $item->title }}</a></h3>
                                <p class="nw-by"><time datetime="{{ $item->published_at->toDateString() }}">{{ $item->published_at->translatedFormat('j F Y') }}</time></p>
                            </div>
                            <a class="nw-row-img" href="{{ $item->url }}" tabindex="-1" aria-hidden="true"><img src="{{ $item->thumbnail_url }}" alt="" width="240" height="180" loading="lazy"></a>
                        </article>
                    @endforeach
                </section>
            @endif
        </article>

        <x-sidebar :show-latest="true" />
    </div>
@endsection
