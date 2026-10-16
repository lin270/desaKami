@extends('layouts.app')

@section('title', 'Galeri')
@section('description', 'Album foto kegiatan '.$site['site_name'].'.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Galeri</li>
                </ol>
            </nav>
            <h1>Album Kenangan Desa</h1>
            <p class="lead">Kumpulan album foto kegiatan dan momen warga {{ $site['site_name'] }}.</p>
        </div>
    </div>

    <section class="alb-paper">
        <div class="wide">
            @if ($galleries->isNotEmpty())
                <div class="alb-grid">
                    @foreach ($galleries as $g)
                        @php
                            $coverId = $g->cover_image_id ?: $g->images->first()?->id;
                            $back = $g->images->reject(fn ($i) => $i->id === $coverId)->take(2)->values();
                        @endphp
                        <article class="alb">
                            <a href="{{ $g->url }}" class="alb-stack" aria-label="Buka album {{ $g->title }}">
                                <span class="alb-photo p3" aria-hidden="true">@if ($back->get(1))<img src="{{ $back->get(1)->image_url }}" alt="" loading="lazy">@endif</span>
                                <span class="alb-photo p2" aria-hidden="true">@if ($back->get(0))<img src="{{ $back->get(0)->image_url }}" alt="" loading="lazy">@endif</span>
                                <span class="alb-photo p1">
                                    <img src="{{ $g->cover_url }}" alt="Sampul album {{ $g->title }}" width="600" height="450" loading="lazy">
                                    <span class="alb-strip">{{ $g->images_count }} foto</span>
                                </span>
                                <span class="alb-tape" aria-hidden="true"></span>
                            </a>
                            <div class="alb-info">
                                <h2><a href="{{ $g->url }}">{{ $g->title }}</a></h2>
                                @if ($g->taken_at)
                                    <p class="alb-date"><time datetime="{{ $g->taken_at->toDateString() }}">{{ $g->taken_at->translatedFormat('j F Y') }}</time></p>
                                @endif
                                @if ($g->description)<p class="alb-desc">{{ $g->description }}</p>@endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $galleries->links() }}
            @else
                <div class="kl-empty">
                    <strong>Belum ada album foto</strong>
                    Album akan tampil di sini setelah dibuat dari dashboard admin.
                </div>
            @endif
        </div>
    </section>
@endsection
