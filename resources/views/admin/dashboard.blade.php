@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <x-admin.page-title title="Dashboard">
        <a class="btn" href="{{ route('admin.posts.create') }}">+ Tulis Berita</a>
    </x-admin.page-title>

    <div class="stat-grid">
        <div class="stat-card"><b>{{ number_format($totalPosts, 0, ',', '.') }}</b><span>Berita @if ($totalDrafts) ({{ $totalDrafts }} draft)@endif</span></div>
        <div class="stat-card"><b>{{ number_format($totalAnnouncements, 0, ',', '.') }}</b><span>Pengumuman</span></div>
        <div class="stat-card"><b>{{ number_format($totalEvents, 0, ',', '.') }}</b><span>Agenda @if ($upcomingEvents) ({{ $upcomingEvents }} mendatang)@endif</span></div>
        @if ($totalLocations !== null)
            <div class="stat-card"><b>{{ number_format($totalLocations, 0, ',', '.') }}</b><span>Lokasi peta</span></div>
        @endif
        @if ($pendingAspirations !== null)
            <div class="stat-card"><b>{{ number_format($pendingAspirations, 0, ',', '.') }}</b><span>Aspirasi belum dibalas</span></div>
        @endif
        <div class="stat-card"><b>{{ number_format($activeLostItems, 0, ',', '.') }}</b><span>Laporan barang aktif</span></div>
        <div class="stat-card"><b>{{ number_format($totalGalleries, 0, ',', '.') }}</b><span>Album galeri</span></div>
        <div class="stat-card"><b>{{ number_format($totalViews, 0, ',', '.') }}</b><span>Total views berita</span></div>
    </div>

    <div class="two-col">
        <section class="card">
            <h2>Berita Terbaru</h2>
            @if ($recentPosts->isNotEmpty())
                <div class="tbl-wrap" style="border:0">
                    <table class="tbl">
                        <thead><tr><th>Judul</th><th>Status</th><th class="num">Views</th></tr></thead>
                        <tbody>
                            @foreach ($recentPosts as $p)
                                <tr>
                                    <td class="judul">
                                        @can('update', $p)
                                            <a href="{{ route('admin.posts.edit', $p) }}">{{ $p->title }}</a>
                                        @else
                                            {{ $p->title }}
                                        @endcan
                                        <small>{{ $p->category->name }} &middot; {{ $p->author->name }}</small>
                                    </td>
                                    <td><span @class(['badge', 'badge-ok' => $p->status === 'published', 'badge-warn' => $p->status !== 'published'])>{{ $p->status === 'published' ? 'Terbit' : 'Draft' }}</span></td>
                                    <td class="num">{{ number_format($p->views, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p style="margin:.75rem 0 0"><a href="{{ route('admin.posts.index') }}">Lihat semua berita &rarr;</a></p>
            @else
                <p class="empty">Belum ada berita.</p>
            @endif
        </section>

        <section class="card">
            <h2>Aktivitas Terbaru</h2>
            @if ($activity->isNotEmpty())
                <ul class="activity">
                    @foreach ($activity as $a)
                        <li>
                            <span class="tipe"><span class="badge">{{ $a['type'] }}</span></span>
                            <div>
                                <p>
                                    @if ($a['url'])<a href="{{ $a['url'] }}">{{ $a['title'] }}</a>@else{{ $a['title'] }}@endif
                                </p>
                                <small>{{ $a['new'] ? 'Dibuat' : 'Diperbarui' }} {{ $a['at']->diffForHumans() }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="empty">Belum ada aktivitas.</p>
            @endif
        </section>
    </div>
@endsection
