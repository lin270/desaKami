@extends('layouts.app')

@section('description', 'Portal resmi '.$site['site_name'].': profil, berita, peta, galeri, pengumuman, dan informasi layanan desa.')

@push('head')
    {!! \App\Support\JsonLd::organization($site, $logoUrl) !!}
@endpush

{{-- ============ HERO SINEMATIK ============ --}}
@section('hero')
    <section class="kl-hero" aria-labelledby="hero-title">
        <div class="kl-hero-media" aria-hidden="{{ $heroImage ? 'false' : 'true' }}">
            @if ($heroImage)
                <img src="{{ $heroImage }}" alt="Suasana {{ $site['site_name'] }}" width="1600" height="900" fetchpriority="high">
            @else
                @include('partials.hero-landscape')
            @endif
        </div>

        <div class="wide kl-hero-body">
            <p class="kl-hero-eyebrow">Portal resmi {{ $site['site_name'] }}</p>
            <h1 id="hero-title">Desa Klepu, <em>harmoni alam</em> dan kehidupan.</h1>
            <p class="kl-hero-lead">Mengenal kehidupan, potensi, dan cerita masyarakat Desa Klepu melalui satu ruang digital.</p>
            <div class="kl-hero-cta">
                <a class="btn btn-light" href="{{ url('/profil') }}">Kenali Profil Desa</a>
                <a class="btn btn-outline" href="{{ url('/peta-desa') }}">Jelajahi Peta</a>
            </div>
        </div>

        <a class="kl-scroll" href="#pengantar" aria-label="Gulir ke bawah">Gulir</a>
        @unless ($heroImage)
            <p class="kl-hero-credit">Ilustrasi. Unggah foto desa di menu Profil Desa (admin) untuk menggantinya.</p>
        @endunless
    </section>
@endsection

@section('content')
    <h1 class="visually-hidden">{{ $site['site_name'] }} - {{ $site['site_tagline'] }}</h1>

    {{-- ============ PENGANTAR ============ --}}
    <section class="kl-section" id="pengantar" aria-labelledby="pengantar-judul">
        <div class="wide">
            <div class="kl-intro">
                <div class="kl-intro-text reveal">
                    <p class="kl-eyebrow">Tentang desa</p>
                    <h2 class="kl-h2" id="pengantar-judul">Satu ruang untuk mengenal Desa Klepu.</h2>
                    @if ($profileExcerpt !== '')
                        <p>{{ $profileExcerpt }}</p>
                    @else
                        <p class="muted">Profil desa belum diisi. Pengelola dapat melengkapinya di dashboard admin.</p>
                    @endif
                    <a class="link-arrow" href="{{ url('/profil') }}">Baca profil lengkap</a>
                </div>

                <div class="kl-intro-photos reveal">
                    <figure class="kl-photo kl-photo-a">
                        @if ($introPhoto)
                            <img src="{{ $introPhoto }}" alt="Dokumentasi {{ $site['site_name'] }}" width="800" height="1000" loading="lazy">
                        @else
                            <div class="kl-photo-ph">Foto desa akan tampil di sini setelah diunggah.</div>
                        @endif
                    </figure>
                    @if ($secondPhoto)
                        <figure class="kl-photo kl-photo-b">
                            <img src="{{ $secondPhoto }}" alt="Kegiatan di {{ $site['site_name'] }}" width="400" height="400" loading="lazy">
                        </figure>
                    @endif
                </div>
            </div>

            @if ($stats->isNotEmpty())
                <div class="kl-facts reveal" role="list" aria-label="Data desa">
                    @foreach ($stats as $s)
                        <div class="kl-fact" role="listitem">
                            <b>{{ $s->value }}@if ($s->unit) <small>{{ $s->unit }}</small>@endif</b>
                            <span>{{ $s->label }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- ============ BERITA & CERITA DESA ============ --}}
    <section class="kl-section kl-stories" aria-labelledby="berita-judul">
        <div class="wide">
            <div class="kl-head reveal">
                <div>
                    <p class="kl-eyebrow">Berita dan cerita desa</p>
                    <h2 class="kl-h2" id="berita-judul">Kabar terbaru dari warga.</h2>
                </div>
                <a class="link-arrow" href="{{ url('/berita') }}">Semua berita</a>
            </div>

            @if ($featured)
                <div class="kl-story-grid">
                    <article class="reveal">
                        <a class="kl-lead-story" href="{{ $featured->url }}">
                            <img src="{{ $featured->thumbnail_url }}" alt="" width="1200" height="800" loading="lazy">
                            <div class="kl-lead-body">
                                <span class="kl-tag">{{ $featured->category->name }}</span>
                                <h3>{{ $featured->title }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($featured->excerpt_text, 150) }}</p>
                                <p class="meta"><time datetime="{{ $featured->published_at->toDateString() }}">{{ $featured->published_at->translatedFormat('j F Y') }}</time></p>
                            </div>
                        </a>
                    </article>

                    @if ($sideHeadlines->isNotEmpty())
                        <div class="kl-side-stories reveal">
                            @foreach ($sideHeadlines as $p)
                                <article class="kl-mini">
                                    <a href="{{ $p->url }}" class="kl-mini-img" tabindex="-1" aria-hidden="true">
                                        <img src="{{ $p->thumbnail_url }}" alt="" width="224" height="224" loading="lazy">
                                    </a>
                                    <div>
                                        <a class="kl-tag" href="{{ $p->category->url }}">{{ $p->category->name }}</a>
                                        <h3><a href="{{ $p->url }}">{{ $p->title }}</a></h3>
                                        <p class="meta"><time datetime="{{ $p->published_at->toDateString() }}">{{ $p->published_at->translatedFormat('j F Y') }}</time></p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                @if ($latestPosts->isNotEmpty())
                    <div class="kl-more-grid">
                        @foreach ($latestPosts->take(6) as $post)
                            <div class="reveal"><x-post-card :post="$post" /></div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="kl-empty">
                    <strong>Belum ada berita</strong>
                    Berita dan cerita desa akan tampil di sini setelah diterbitkan dari dashboard admin.
                </div>
            @endif
        </div>
    </section>

    {{-- ============ JELAJAHI DESA ============ --}}
    <section class="kl-section kl-explore" aria-labelledby="jelajah-judul">
        <div class="wide">
            <div class="kl-head reveal">
                <div>
                    <p class="kl-eyebrow">Jelajahi desa</p>
                    <h2 class="kl-h2" id="jelajah-judul">Potensi dan tempat di Desa Klepu.</h2>
                </div>
                <a class="link-arrow" style="color:var(--emas);border-color:var(--emas)" href="{{ url('/potensi') }}">Semua potensi</a>
            </div>

            <div class="kl-explore-grid">
                @foreach ($potentials->take(3) as $p)
                    <a class="kl-place reveal" href="{{ $p->url }}">
                        <img src="{{ $p->image_url }}" alt="" width="600" height="600" loading="lazy">
                        <div class="kl-place-body">
                            <span class="kl-tag">{{ $p->category_label }}</span>
                            <h3>{{ $p->title }}</h3>
                            <p>{{ $p->summary_text }}</p>
                        </div>
                    </a>
                @endforeach

                <div class="kl-mapcta reveal">
                    <div>
                        <p class="kl-eyebrow" style="color:var(--emas)">Peta interaktif</p>
                        <h3>Telusuri lokasi di peta.</h3>
                        @if ($mapCount > 0)
                            <p>{{ $mapCount }} lokasi sudah tercatat oleh pengelola desa.</p>
                            @if ($mapCategories->isNotEmpty())
                                <div class="kl-chips">
                                    @foreach ($mapCategories->take(5) as $cat)<span>{{ $cat }}</span>@endforeach
                                </div>
                            @endif
                        @else
                            <p>Lokasi penting desa sedang disiapkan oleh pengelola.</p>
                        @endif
                    </div>
                    <a class="btn btn-light" href="{{ url('/peta-desa') }}">Buka Peta Desa</a>
                </div>
            </div>

            @if ($potentials->isEmpty())
                <p class="kosong" style="color:#cfd6c8">Data potensi desa belum ditambahkan.</p>
            @endif
        </div>
    </section>

    {{-- ============ GALERI VISUAL ============ --}}
    @if ($galleryImages->isNotEmpty())
        <section class="kl-section" aria-labelledby="galeri-judul">
            <div class="wide">
                <div class="kl-head reveal">
                    <div>
                        <p class="kl-eyebrow">Galeri visual</p>
                        <h2 class="kl-h2" id="galeri-judul">Potret kegiatan warga.</h2>
                    </div>
                    <a class="link-arrow" href="{{ url('/galeri') }}">Buka galeri</a>
                </div>

                <div class="kl-gallery-grid reveal">
                    @foreach ($galleryImages as $img)
                        <a @class(['kl-shot', 'kl-shot-wide' => $loop->first]) href="{{ $img->gallery->url }}">
                            <img src="{{ $img->image_url }}" alt="{{ $img->caption ?: $img->gallery->title }}" width="{{ $loop->first ? 800 : 400 }}" height="400" loading="lazy">
                            <span>{{ $img->caption ?: $img->gallery->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ LAYANAN & PARTISIPASI WARGA ============ --}}
    <section class="kl-section kl-services" aria-labelledby="layanan-judul">
        <div class="wide">
            <div class="kl-head reveal">
                <div>
                    <p class="kl-eyebrow">Layanan dan partisipasi warga</p>
                    <h2 class="kl-h2" id="layanan-judul">Informasi yang mudah dijangkau.</h2>
                </div>
            </div>

            <div class="kl-tiles reveal">
                <a class="kl-tile" href="{{ url('/informasi') }}"><span class="kl-tile-no">01</span><h3>Informasi Layanan</h3><p>Panduan pelayanan dan informasi publik desa.</p></a>
                <a class="kl-tile" href="{{ url('/pengumuman') }}"><span class="kl-tile-no">02</span><h3>Pengumuman</h3><p>Pengumuman resmi dari pemerintah desa.</p></a>
                <a class="kl-tile" href="{{ url('/agenda') }}"><span class="kl-tile-no">03</span><h3>Kalender Warga</h3><p>Jadwal kegiatan dan agenda desa.</p></a>
                <a class="kl-tile" href="{{ url('/aspirasi') }}"><span class="kl-tile-no">04</span><h3>Ruang Aspirasi</h3><p>Sampaikan kritik dan saran untuk desa.</p></a>
                <a class="kl-tile" href="{{ url('/barang-hilang') }}"><span class="kl-tile-no">05</span><h3>Barang Hilang</h3><p>Laporan barang hilang dan ditemukan.</p></a>
                <a class="kl-tile" href="{{ url('/apbdes') }}"><span class="kl-tile-no">06</span><h3>APBDes</h3><p>Dokumen transparansi anggaran desa.</p></a>
            </div>

            <div class="kl-cols">
                <div class="reveal">
                    <div class="kl-col-title"><h2>Pengumuman</h2><a href="{{ url('/pengumuman') }}">Lihat semua</a></div>
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

                <div class="reveal">
                    <div class="kl-col-title"><h2>Kalender Warga</h2><a href="{{ url('/agenda') }}">Lihat kalender</a></div>
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

                <div class="reveal">
                    <div class="kl-col-title"><h2>Barang Hilang &amp; Ditemukan</h2><a href="{{ url('/barang-hilang') }}">Lihat semua</a></div>
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

                <div class="reveal">
                    <div class="kl-col-title"><h2>Transparansi APBDes</h2><a href="{{ url('/apbdes') }}">Semua tahun</a></div>
                    @if ($budget)
                        <article class="apbdes-berkas apbdes-berkas-kecil">
                            <div class="apbdes-tahun" aria-hidden="true">{{ $budget->year }}</div>
                            <div class="apbdes-info">
                                <h3>{{ $budget->title ?: 'APBDes '.$site['site_name'].' Tahun '.$budget->year }}</h3>
                                <p class="muted">PDF{{ $budget->file_size_label ? ' · '.$budget->file_size_label : '' }}</p>
                            </div>
                            <a class="btn" href="{{ $budget->download_url }}" download>Unduh PDF</a>
                        </article>
                    @else
                        <p class="kosong">Dokumen APBDes belum dipublikasikan.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
