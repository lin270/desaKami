@extends('layouts.app')

@section('title', $category ? 'Berita '.$category->name : 'Berita')
@section('description', $category
    ? ($category->description ?: 'Kumpulan berita kategori '.$category->name.' dari '.$site['site_name'].'.')
    : 'Berita terbaru dan terpopuler seputar pemerintahan, kegiatan, dan pembangunan '.$site['site_name'].'.')

@php
    $readTime = fn ($p) => max(1, (int) ceil(str_word_count(strip_tags((string) $p->content)) / 200));
    $front = $posts->onFirstPage() && $posts->count() > 0;
    $lead = $front ? $posts->first() : null;
    $secondary = $front ? $posts->slice(1, 2) : collect();
    $rows = $front ? $posts->slice(3) : $posts;
@endphp

@section('content')
    {{-- Kepala surat kabar --}}
    <header class="nw-masthead">
        <div class="wide">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    @if ($category)
                        <li><a href="{{ url('/berita') }}">Berita</a></li>
                        <li aria-current="page">{{ $category->name }}</li>
                    @else
                        <li aria-current="page">Berita</li>
                    @endif
                </ol>
            </nav>
            <p class="nw-dateline"><time datetime="{{ now()->toDateString() }}">{{ now()->translatedFormat('l, j F Y') }}</time> &middot; {{ $site['site_name'] }}</p>
            <h1 class="nw-title">{{ $category ? $category->name : 'Berita Desa' }}</h1>
            <p class="nw-sub">{{ $category && $category->description ? $category->description : 'Kabar terbaru, kegiatan, dan pembangunan dari warga dan pemerintah desa.' }}</p>
        </div>

        <div class="nw-sections">
            <div class="wide nw-sections-in">
                <nav class="nw-tabs" aria-label="Kategori berita">
                    <a href="{{ url('/berita') }}" @class(['active' => ! $category]) @if (! $category) aria-current="page" @endif>Terbaru</a>
                    @foreach ($categories as $c)
                        <a href="{{ $c->url }}" @class(['active' => $category && $category->id === $c->id]) @if ($category && $category->id === $c->id) aria-current="page" @endif>{{ $c->name }}</a>
                    @endforeach
                </nav>
                <form class="nw-search" action="{{ url('/search') }}" method="GET" role="search">
                    <label for="nw-q" class="visually-hidden">Cari berita</label>
                    <input id="nw-q" type="search" name="q" placeholder="Cari berita" required minlength="2" maxlength="100">
                    <button type="submit" aria-label="Cari"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg></button>
                </form>
            </div>
        </div>
    </header>

    <div class="wide nw-body">
        @if ($posts->isEmpty())
            <div class="kl-empty">
                <strong>Belum ada berita{{ $category ? ' pada kategori ini' : '' }}</strong>
                Berita akan tampil di sini setelah diterbitkan dari dashboard admin.
            </div>
        @else
            {{-- Halaman depan: berita utama + dua berita pendamping --}}
            @if ($lead)
                <section class="nw-front" aria-label="Berita utama">
                    <article class="nw-lead">
                        <a class="nw-lead-img" href="{{ $lead->url }}"><img src="{{ $lead->thumbnail_url }}" alt="" width="1200" height="750" fetchpriority="high"></a>
                        <div class="nw-lead-text">
                            <a class="nw-kicker" href="{{ $lead->category->url }}">{{ $lead->category->name }}</a>
                            <h2><a href="{{ $lead->url }}">{{ $lead->title }}</a></h2>
                            <p class="nw-deck">{{ \Illuminate\Support\Str::limit($lead->excerpt_text, 220) }}</p>
                            <p class="nw-by"><b>{{ $lead->author->name }}</b><time datetime="{{ $lead->published_at->toDateString() }}">{{ $lead->published_at->translatedFormat('j F Y') }}</time><span>{{ $readTime($lead) }} menit baca</span></p>
                        </div>
                    </article>

                    @if ($secondary->isNotEmpty())
                        <div class="nw-secondary">
                            @foreach ($secondary as $p)
                                <article class="nw-sec">
                                    <a class="nw-sec-img" href="{{ $p->url }}" tabindex="-1" aria-hidden="true"><img src="{{ $p->thumbnail_url }}" alt="" width="600" height="400" loading="lazy"></a>
                                    <a class="nw-kicker" href="{{ $p->category->url }}">{{ $p->category->name }}</a>
                                    <h3><a href="{{ $p->url }}">{{ $p->title }}</a></h3>
                                    <p class="nw-by"><time datetime="{{ $p->published_at->toDateString() }}">{{ $p->published_at->translatedFormat('j F Y') }}</time><span>{{ $readTime($p) }} menit baca</span></p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif

            <div class="nw-cols">
                <section aria-label="Daftar berita">
                    <div class="nw-sorthead">
                        <h2>{{ $front ? 'Berita lainnya' : 'Halaman '.$posts->currentPage() }}</h2>
                        <div class="sort-tabs">
                            <a href="{{ url()->current() }}" @class(['active' => $sort === 'terbaru'])>Terbaru</a>
                            <a href="{{ url()->current() }}?urut=populer" @class(['active' => $sort === 'populer'])>Terpopuler</a>
                        </div>
                    </div>

                    @foreach ($rows as $p)
                        <article class="nw-row">
                            <div class="nw-row-text">
                                <a class="nw-kicker" href="{{ $p->category->url }}">{{ $p->category->name }}</a>
                                <h3><a href="{{ $p->url }}">{{ $p->title }}</a></h3>
                                <p class="nw-row-excerpt">{{ \Illuminate\Support\Str::limit($p->excerpt_text, 170) }}</p>
                                <p class="nw-by"><b>{{ $p->author->name }}</b><time datetime="{{ $p->published_at->toDateString() }}">{{ $p->published_at->translatedFormat('j F Y') }}</time><span>{{ $readTime($p) }} menit baca</span></p>
                            </div>
                            <a class="nw-row-img" href="{{ $p->url }}" tabindex="-1" aria-hidden="true"><img src="{{ $p->thumbnail_url }}" alt="" width="360" height="270" loading="lazy"></a>
                        </article>
                    @endforeach

                    {{ $posts->links() }}
                </section>

                <x-sidebar />
            </div>
        @endif
    </div>
@endsection
