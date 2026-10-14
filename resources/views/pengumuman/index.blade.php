@extends('layouts.app')

@section('title', 'Pengumuman')
@section('description', 'Pengumuman resmi dari Pemerintah Desa Klepu untuk masyarakat.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Pengumuman</li>
                </ol>
            </nav>
            <h1>Pengumuman Desa</h1>
            <p class="lead">Informasi penting dan pemberitahuan resmi dari pemerintah desa.</p>
        </div>
    </div>

    <section class="section">
        <div class="container layout">
            <div>
                @if ($categories->count() > 1)
                    <div class="chips" aria-label="Filter kategori">
                        <a href="{{ url('/pengumuman') }}" @class(['chip', 'active' => ! $selected])>Semua</a>
                        @foreach ($categories as $c)
                            <a href="{{ url('/pengumuman') }}?kategori={{ urlencode($c) }}" @class(['chip', 'active' => $selected === $c])>{{ $c }}</a>
                        @endforeach
                    </div>
                @endif

                @forelse ($announcements as $a)
                    <article @class(['pengumuman-item', 'penting' => $a->is_important])>
                        <p class="meta">
                            @if ($a->is_important)<span class="badge badge-penting">Penting</span>@endif
                            <span class="badge">{{ $a->category }}</span>
                            <time datetime="{{ $a->published_at->toDateString() }}">{{ $a->published_at->translatedFormat('j F Y') }}</time>
                        </p>
                        <h2><a href="{{ $a->url }}">{{ $a->title }}</a></h2>
                        <p class="pengumuman-ringkas">{{ \Illuminate\Support\Str::limit(trim(strip_tags(\Illuminate\Support\Str::markdown($a->content, ['html_input' => 'strip']))), 150) }}</p>
                    </article>
                @empty
                    <p class="kosong">Belum ada pengumuman.</p>
                @endforelse

                {{ $announcements->links() }}
            </div>

            <x-sidebar />
        </div>
    </section>
@endsection
