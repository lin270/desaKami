@extends('layouts.app')

@section('title', $category ? 'Berita '.$category->name : 'Berita')
@section('description', $category
    ? ($category->description ?: 'Kumpulan berita kategori '.$category->name.' dari Desa Klepu.')
    : 'Berita terbaru dan terpopuler seputar pemerintahan, kegiatan, dan pembangunan Desa Klepu.')

@section('content')
    <div class="page-head">
        <div class="container">
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
            <h1>{{ $category ? 'Kategori: '.$category->name : 'Berita Desa' }}</h1>
            <p class="lead">
                {{ $category && $category->description ? $category->description : 'Kabar terbaru dari Desa Klepu.' }}
            </p>
        </div>
    </div>

    <section class="section">
        <div class="container layout">
            <div>
                <div class="chips" aria-label="Filter kategori">
                    <a href="{{ url('/berita') }}" @class(['chip', 'active' => ! $category])>Semua</a>
                    @foreach ($categories as $c)
                        <a href="{{ $c->url }}" @class(['chip', 'active' => $category && $category->id === $c->id])>{{ $c->name }}</a>
                    @endforeach
                </div>

                <div class="sort-tabs">
                    <a href="{{ url()->current() }}" @class(['active' => $sort === 'terbaru'])>Terbaru</a>
                    <a href="{{ url()->current() }}?urut=populer" @class(['active' => $sort === 'populer'])>Terpopuler</a>
                </div>

                @if ($posts->isNotEmpty())
                    <div class="grid-news">
                        @foreach ($posts as $post)
                            <x-post-card :post="$post" :detail="true" />
                        @endforeach
                    </div>

                    {{ $posts->links() }}
                @else
                    <p class="kosong">Belum ada berita{{ $category ? ' pada kategori ini' : '' }}.</p>
                @endif
            </div>

            <x-sidebar />
        </div>
    </section>
@endsection
