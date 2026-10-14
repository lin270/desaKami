@extends('layouts.app')

@section('title', $q !== '' ? 'Hasil pencarian: '.$q : 'Pencarian')
@section('description', 'Cari berita dan informasi di website resmi Desa Klepu.')

@push('head')
    <meta name="robots" content="noindex, follow">
@endpush

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Pencarian</li>
                </ol>
            </nav>
            <h1>Pencarian Berita</h1>

            <form action="{{ url('/search') }}" method="GET" role="search" class="search-big">
                <label for="q-halaman" class="visually-hidden">Kata kunci</label>
                <input type="search" id="q-halaman" name="q" value="{{ $q }}" placeholder="Ketik kata kunci..." required minlength="2" maxlength="100">
                <button type="submit" class="btn">Cari</button>
            </form>
        </div>
    </div>

    <section class="section">
        <div class="container layout">
            <div>
                @if ($q === '')
                    <p class="kosong">Ketik kata kunci untuk mencari berita berdasarkan judul, isi, atau ringkasan.</p>
                @elseif ($posts === null)
                    <p class="kosong">Masukkan minimal 2 karakter untuk mencari.</p>
                @elseif ($posts->isEmpty())
                    <p class="kosong">Tidak ada berita yang cocok dengan &ldquo;{{ $q }}&rdquo;. Coba kata kunci lain.</p>
                @else
                    <p class="hasil-info">{{ number_format($posts->total(), 0, ',', '.') }} berita ditemukan untuk &ldquo;{{ $q }}&rdquo;</p>

                    <div class="grid-news">
                        @foreach ($posts as $post)
                            <x-post-card :post="$post" :detail="true" />
                        @endforeach
                    </div>

                    {{ $posts->links() }}
                @endif
            </div>

            <x-sidebar />
        </div>
    </section>
@endsection
