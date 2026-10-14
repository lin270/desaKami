@props(['showLatest' => false])

<aside class="sidebar" aria-label="Sidebar">
    <section class="widget">
        <h2 class="widget-title">Berita Populer</h2>
        @if ($popularPosts->isNotEmpty())
            <ol class="populer">
                @foreach ($popularPosts as $i => $p)
                    <li>
                        <span class="no" aria-hidden="true">{{ $i + 1 }}</span>
                        <div>
                            <a href="{{ $p->url }}">{{ $p->title }}</a>
                            <span class="meta">{{ number_format($p->views, 0, ',', '.') }} dibaca</span>
                        </div>
                    </li>
                @endforeach
            </ol>
        @else
            <p class="kosong">Belum ada data.</p>
        @endif
    </section>

    @if ($showLatest)
        <section class="widget">
            <h2 class="widget-title">Berita Terbaru</h2>
            <ul class="daftar-link">
                @foreach ($latestPosts as $p)
                    <li>
                        <a href="{{ $p->url }}">{{ $p->title }}</a>
                        <span class="meta">{{ $p->published_at->translatedFormat('j F Y') }}</span>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    <section class="widget">
        <h2 class="widget-title">Kategori Berita</h2>
        <ul class="kategori-list">
            @foreach ($sidebarCategories as $c)
                <li>
                    <a href="{{ $c->url }}">{{ $c->name }}</a>
                    <span>{{ $c->posts_count }}</span>
                </li>
            @endforeach
        </ul>
    </section>
</aside>
