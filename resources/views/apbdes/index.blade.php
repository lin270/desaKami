@extends('layouts.app')

@section('title', 'APBDes')
@section('description', 'Unduh dokumen Anggaran Pendapatan dan Belanja Desa (APBDes) Desa Klepu per tahun anggaran.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">APBDes</li>
                </ol>
            </nav>
            <h1>APBDes</h1>
            <p class="lead">Anggaran Pendapatan dan Belanja Desa Klepu, dipublikasikan sebagai bentuk keterbukaan informasi. Pilih tahun lalu unduh dokumennya (PDF).</p>
        </div>
    </div>

    <div class="container halaman">
        @forelse ($budgets as $b)
            <article class="apbdes-berkas">
                <div class="apbdes-tahun" aria-hidden="true">{{ $b->year }}</div>
                <div class="apbdes-info">
                    <h2>{{ $b->title ?: 'APBDes Desa Klepu Tahun '.$b->year }}</h2>
                    @if ($b->notes)<p>{{ $b->notes }}</p>@endif
  <p class="muted">PDF{{ $b->file_size_label ? ' · '.$b->file_size_label : '' }}</p>
                </div>
                <a class="btn" href="{{ $b->download_url }}" download>Unduh PDF {{ $b->year }}</a>
            </article>
        @empty
            <p class="kosong">Dokumen APBDes belum dipublikasikan.</p>
        @endforelse
    </div>
@endsection
