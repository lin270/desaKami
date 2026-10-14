@extends('layouts.app')

@push('head')
    {!! \App\Support\JsonLd::organization($site, $logoUrl) !!}
@endpush

@section('content')
    <h1 class="visually-hidden">{{ $site['site_name'] }} - {{ $site['site_tagline'] }}</h1>

    {{-- 2. Headline berita --}}
    @if ($featured)
        <section class="container hero" aria-label="Berita utama">
            <article class="hero-main">
                <a href="{{ $featured->url }}" class="hero-img" tabindex="-1" aria-hidden="true">
                    <img src="{{ $featured->thumbnail_url }}" alt="" width="1200" height="675" fetchpriority="high">
                </a>
                <div class="hero-body">
                    <a class="kategori" href="{{ $featured->category->url }}">{{ $featured->category->name }}</a>
                    <h2 class="hero-title"><a href="{{ $featured->url }}">{{ $featured->title }}</a></h2>
                    <p class="hero-excerpt">{{ $featured->excerpt_text }}</p>
                    <p class="meta">
                        <time datetime="{{ $featured->published_at->toDateString() }}">{{ $featured->published_at->translatedFormat('j F Y') }}</time>
                        <span>{{ $featured->author->name }}</span>
                    </p>
                </div>
            </article>

            @if ($sideHeadlines->isNotEmpty())
                <div class="hero-side">
                    @foreach ($sideHeadlines as $p)
                        <article class="side-item">
                            <a href="{{ $p->url }}" class="side-thumb" tabindex="-1" aria-hidden="true">
                                <img src="{{ $p->thumbnail_url }}" alt="" width="200" height="140" loading="lazy">
                            </a>
                            <div>
                                <a class="kategori" href="{{ $p->category->url }}">{{ $p->category->name }}</a>
                                <h3><a href="{{ $p->url }}">{{ $p->title }}</a></h3>
                                <p class="meta"><time datetime="{{ $p->published_at->toDateString() }}">{{ $p->published_at->translatedFormat('j F Y') }}</time></p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    {{-- 3 & 4. Berita terbaru + sidebar populer --}}
    <section class="section">
        <div class="container layout">
            <div>
                <x-section-title title="Berita Terbaru" :href="url('/berita')" />

                @if ($latestPosts->isNotEmpty())
                    <div class="grid-news">
                        @foreach ($latestPosts as $post)
                            <x-post-card :post="$post" />
                        @endforeach
                    </div>
                @else
                    <p class="kosong">Belum ada berita yang diterbitkan.</p>
                @endif
            </div>

            <x-sidebar />
        </div>
    </section>

    {{-- 5. Informasi desa --}}
    <section class="section section-alt" id="informasi-desa">
        <div class="container">
            <x-section-title title="Informasi Desa" :href="url('/profil')" link-text="Profil lengkap" />

            <div class="info-grid">
                <div class="profil-singkat">
                    <h3>Tentang Desa Klepu</h3>
                    <p>{{ $profileExcerpt ?: 'Profil desa belum diisi.' }}</p>
                    <a class="btn" href="{{ url('/profil') }}">Selengkapnya</a>
                </div>

                <div class="stats">
                    @foreach ($stats as $s)
                        <div class="stat">
                            <span class="stat-nilai">{{ $s->value }}</span>
                            @if ($s->unit)<span class="stat-unit">{{ $s->unit }}</span>@endif
                            <span class="stat-label">{{ $s->label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- 6 & 7. Pengumuman + agenda --}}
    <section class="section">
        <div class="container duo">
            <div>
                <x-section-title title="Pengumuman Desa" :href="url('/pengumuman')" />
                @forelse ($announcements as $a)
                    <article class="pengumuman">
                        <p class="meta">
                            @if ($a->is_important)<span class="badge badge-penting">Penting</span>@endif
                            <span class="badge">{{ $a->category }}</span>
                            <time datetime="{{ $a->published_at->toDateString() }}">{{ $a->published_at->translatedFormat('j F Y') }}</time>
                        </p>
                        <h3><a href="{{ $a->url }}">{{ $a->title }}</a></h3>
                    </article>
                @empty
                    <p class="kosong">Belum ada pengumuman.</p>
                @endforelse
            </div>

            <div>
                <x-section-title title="Kalender Warga" :href="url('/agenda')" />
                @forelse ($events as $e)
                    <article class="agenda">
                        <time class="tanggal" datetime="{{ $e->date->toDateString() }}">
                            <span class="hari">{{ $e->date->format('d') }}</span>
                            <span class="bulan">{{ $e->date->translatedFormat('M') }}</span>
                        </time>
                        <div>
                            <h3>{{ $e->title }}</h3>
                            <p class="meta">
                                <span>{{ substr($e->start_time, 0, 5) }}@if ($e->end_time) - {{ substr($e->end_time, 0, 5) }}@endif WIB</span>
                                <span>{{ $e->location }}</span>
                            </p>
                        </div>
                    </article>
                @empty
                    <p class="kosong">Belum ada agenda mendatang.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 8. Potensi desa --}}
    @if ($potentials->isNotEmpty())
        <section class="section section-alt">
            <div class="container">
                <x-section-title title="Potensi Desa" :href="url('/potensi')" />
                <div class="potensi-grid">
                    @foreach ($potentials as $p)
                        <article class="potensi">
                            <a class="kategori" href="{{ url('/potensi') }}?kategori={{ $p->category }}">{{ $p->category_label }}</a>
                            <h3><a href="{{ $p->url }}">{{ $p->title }}</a></h3>
                            <p>{{ $p->summary_text }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Transparansi APBDes + barang hilang & ditemukan --}}
    <section class="section">
        <div class="container duo">
            <div>
                <x-section-title title="Transparansi APBDes" :href="url('/apbdes')" link-text="Semua tahun" />
                @if ($budget)
                    <article class="apbdes-berkas apbdes-berkas-kecil">
                        <div class="apbdes-tahun" aria-hidden="true">{{ $budget->year }}</div>
                        <div class="apbdes-info">
                            <h3>{{ $budget->title ?: 'APBDes Desa Klepu Tahun '.$budget->year }}</h3>
                             <p class="muted">PDF{{ $budget->file_size_label ? ' · '.$budget->file_size_label : '' }}</p>
                        </div>
                        <a class="btn" href="{{ $budget->download_url }}" download>Unduh PDF</a>
                    </article>
                @else
                    <p class="kosong">Dokumen APBDes belum dipublikasikan.</p>
                @endif
            </div>

            <div>
                <x-section-title title="Barang Hilang &amp; Ditemukan" :href="url('/barang-hilang')" />
                @forelse ($lostItems as $it)
                    <article class="pengumuman">
                        <p class="meta">
                            <span @class(['badge', 'badge-hilang' => $it->type === 'hilang', 'badge-ditemukan' => $it->type === 'ditemukan'])>{{ $it->type_label }}</span>
                            <time datetime="{{ $it->event_date->toDateString() }}">{{ $it->event_date->translatedFormat('j F Y') }}</time>
                        </p>
                        <h3><a href="{{ $it->url }}">{{ $it->title }}</a></h3>
                    </article>
                @empty
                    <p class="kosong">Tidak ada laporan barang saat ini.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- 9. Galeri --}}
    @if ($galleryImages->isNotEmpty())
        <section class="section">
            <div class="container">
                <x-section-title title="Galeri Kegiatan" :href="url('/galeri')" />
                <div class="galeri-grid">
                    @foreach ($galleryImages as $img)
                        <a href="{{ $img->gallery->url }}">
                            <img src="{{ $img->image_url }}" alt="{{ $img->caption ?: $img->gallery->title }}" width="400" height="300" loading="lazy">
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
