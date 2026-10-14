@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan')
@section('description', 'Halaman yang Anda cari tidak ditemukan.')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    <div class="container error-page">
        <p class="error-kode">404</p>
        <h1>Halaman tidak ditemukan</h1>
        <p>Alamat yang Anda buka tidak tersedia atau sudah dipindahkan.</p>
        <div class="error-aksi">
            <a class="btn" href="{{ url('/') }}">Kembali ke Beranda</a>
            <a class="btn" href="{{ url('/berita') }}">Lihat Berita</a>
        </div>
    </div>
@endsection
