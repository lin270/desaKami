@extends('layouts.app')

@section('title', 'Pemerintahan Desa')
@section('description', 'Struktur pemerintahan Desa Klepu: kepala desa, sekretaris, kepala urusan, kepala seksi, dan kepala dusun.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Pemerintahan</li>
                </ol>
            </nav>
            <h1>Pemerintahan Desa</h1>
            <p class="lead">Perangkat Desa Klepu yang melayani masyarakat.</p>
        </div>
    </div>

    <div class="container halaman">
        @php $ada = false; @endphp

        @foreach ($groups as $key => $label)
            @if (isset($officials[$key]) && $officials[$key]->isNotEmpty())
                @php $ada = true; @endphp
                <section class="pejabat-grup" aria-labelledby="grup-{{ $key }}">
                    <h2 id="grup-{{ $key }}" class="grup-judul">{{ $label }}</h2>
                    <div @class(['pejabat-grid', 'pejabat-tunggal' => $officials[$key]->count() === 1])>
                        @foreach ($officials[$key] as $o)
                            @include('pemerintahan._kartu', ['o' => $o])
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach

        @unless ($ada)
            <p class="kosong">Data perangkat desa belum tersedia.</p>
        @endunless
    </div>
@endsection
