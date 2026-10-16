@extends('layouts.app')

@section('title', 'Pemerintahan Desa')
@section('description', 'Bagan struktur organisasi pemerintahan '.$site['site_name'].': kepala desa, sekretaris, kepala urusan, kepala seksi, dan kepala dusun.')

@php
    $kades = $officials['kepala_desa'] ?? collect();
    $sekdes = $officials['sekretaris'] ?? collect();
    $branches = collect(['kaur', 'kasi', 'kadus'])
        ->filter(fn ($k) => isset($officials[$k]) && $officials[$k]->isNotEmpty());
    $ada = $kades->isNotEmpty() || $sekdes->isNotEmpty() || $branches->isNotEmpty();
@endphp

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Pemerintahan</li>
                </ol>
            </nav>
            <h1>Struktur Pemerintahan Desa</h1>
            <p class="lead">Bagan organisasi perangkat {{ $site['site_name'] }}.</p>
        </div>
    </div>

    <div class="wide org-page">
        @if ($ada)
            <div class="org" role="group" aria-label="Bagan struktur organisasi pemerintahan desa">
                @if ($kades->isNotEmpty())
                    <div class="org-level">
                        @foreach ($kades as $o)
                            @include('pemerintahan._node', ['o' => $o, 'top' => true])
                        @endforeach
                    </div>
                @endif

                @if ($sekdes->isNotEmpty())
                    @if ($kades->isNotEmpty())<span class="org-stem" aria-hidden="true"></span>@endif
                    <div class="org-level">
                        @foreach ($sekdes as $o)
                            @include('pemerintahan._node', ['o' => $o])
                        @endforeach
                    </div>
                @endif

                @if ($branches->isNotEmpty())
                    @if ($kades->isNotEmpty() || $sekdes->isNotEmpty())<span class="org-stem" aria-hidden="true"></span>@endif
                    <div class="org-branches" style="--cols: {{ $branches->count() }}">
                        @foreach ($branches as $key)
                            <section class="org-branch" aria-labelledby="cab-{{ $key }}">
                                <h2 class="org-tag" id="cab-{{ $key }}">{{ $groups[$key] }}</h2>
                                <div class="org-leaves">
                                    @foreach ($officials[$key] as $o)
                                        @include('pemerintahan._node', ['o' => $o])
                                    @endforeach
                                </div>
                            </section>
                        @endforeach
                    </div>
                @endif
            </div>
            <p class="org-note">Bagan disusun otomatis dari kelompok jabatan pada data perangkat desa di dashboard admin.</p>
        @else
            <div class="kl-empty">
                <strong>Data perangkat desa belum tersedia</strong>
                Admin dapat menambahkannya melalui menu Pemerintahan Desa.
            </div>
        @endif
    </div>
@endsection
