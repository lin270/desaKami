@extends('layouts.app')

@section('title', 'Akses Ditolak')
@section('description', 'Anda tidak memiliki akses ke halaman ini.')

@push('head')
    <meta name="robots" content="noindex">
@endpush

@section('content')
    <div class="container error-page">
        <p class="error-kode">403</p>
        <h1>Akses ditolak</h1>
        <p>Akun Anda tidak memiliki izin untuk membuka halaman ini.</p>
        <div class="error-aksi">
            <a class="btn" href="{{ url('/') }}">Kembali ke Beranda</a>
            @auth
                <a class="btn" href="{{ route('admin.dashboard') }}">Ke Dashboard</a>
            @endauth
        </div>
    </div>
@endsection
