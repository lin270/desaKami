@extends('layouts.app')

@section('title', $announcement->title)
@section('description', \Illuminate\Support\Str::limit(trim(strip_tags(\Illuminate\Support\Str::markdown($announcement->content, ['html_input' => 'strip']))), 160))
@section('og_type', 'article')
@if ($announcement->image)
    @section('image', $announcement->image_url)
@endif

@section('content')
    <div class="container layout artikel-layout">
        <article class="artikel">
            @if ($preview)
                <p class="preview-banner" role="status">Pratinjau: pengumuman ini belum terbit dan hanya terlihat oleh admin.</p>
            @endif

            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/pengumuman') }}">Pengumuman</a></li>
                    <li aria-current="page">{{ $announcement->category }}</li>
                </ol>
            </nav>

            <p class="meta" style="margin-bottom:.5rem">
                @if ($announcement->is_important)<span class="badge badge-penting">Penting</span>@endif
                <span class="badge">{{ $announcement->category }}</span>
            </p>
            <h1 class="artikel-judul">{{ $announcement->title }}</h1>
            <p class="meta">
                @if ($announcement->published_at)
                    <time datetime="{{ $announcement->published_at->toIso8601String() }}">{{ $announcement->published_at->translatedFormat('l, j F Y') }}</time>
                @else
                    <span>Draft</span>
                @endif
            </p>

            @if ($announcement->image)
                <figure class="artikel-gambar">
                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" width="1200" height="675">
                </figure>
            @endif

            <div class="prose">{!! $body !!}</div>

            <p style="margin-top:2rem"><a class="btn" href="{{ url('/pengumuman') }}">&larr; Semua pengumuman</a></p>
        </article>

        <aside class="sidebar" aria-label="Pengumuman lainnya">
            <section class="widget">
                <h2 class="widget-title">Pengumuman Lainnya</h2>
                @if ($others->isNotEmpty())
                    <ul class="daftar-link">
                        @foreach ($others as $o)
                            <li>
                                <a href="{{ $o->url }}">{{ $o->title }}</a>
                                <span class="meta">{{ $o->published_at->translatedFormat('j F Y') }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="kosong">Tidak ada pengumuman lain.</p>
                @endif
            </section>
        </aside>
    </div>
@endsection
