@extends('layouts.app')

@section('title', $category ? 'Potensi '.$categories[$category] : 'Potensi Desa')
@section('description', 'Potensi Desa Klepu: UMKM, pertanian, wisata, dan produk unggulan masyarakat.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    @if ($category)
                        <li><a href="{{ url('/potensi') }}">Potensi</a></li>
                        <li aria-current="page">{{ $categories[$category] }}</li>
                    @else
                        <li aria-current="page">Potensi</li>
                    @endif
                </ol>
            </nav>
            <h1>Potensi Desa</h1>
            <p class="lead">UMKM, pertanian, wisata, dan produk unggulan Desa Klepu.</p>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="chips" aria-label="Filter kategori">
                <a href="{{ url('/potensi') }}" @class(['chip', 'active' => ! $category])>Semua</a>
                @foreach ($categories as $key => $label)
                    <a href="{{ url('/potensi') }}?kategori={{ $key }}" @class(['chip', 'active' => $category === $key])>{{ $label }}</a>
                @endforeach
            </div>

            @if ($potentials->isNotEmpty())
                <div class="grid-news grid-3">
                    @foreach ($potentials as $p)
                        <article class="card">
                            <a href="{{ $p->url }}" class="card-img" tabindex="-1" aria-hidden="true">
                                <img src="{{ $p->image_url }}" alt="" width="600" height="375" loading="lazy">
                            </a>
                            <div class="card-body">
                                <a class="kategori" href="{{ url('/potensi') }}?kategori={{ $p->category }}">{{ $p->category_label }}</a>
                                <h2 class="card-title"><a href="{{ $p->url }}">{{ $p->title }}</a></h2>
                                <p class="card-excerpt">{{ $p->summary_text }}</p>
                                @if ($p->location)<p class="meta"><span>{{ $p->location }}</span></p>@endif
                            </div>
                        </article>
                    @endforeach
                </div>

                {{ $potentials->links() }}
            @else
                <p class="kosong">Belum ada data potensi{{ $category ? ' pada kategori ini' : '' }}.</p>
            @endif
        </div>
    </section>
@endsection
