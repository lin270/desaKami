@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')
    <x-admin.page-title title="Galeri Album">
        <a class="btn" href="{{ route('admin.galleries.create') }}">+ Buat Album</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul album..." aria-label="Cari album">
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($galleries->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th></th><th>Album</th><th>Tanggal</th><th class="num">Foto</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($galleries as $g)
                        <tr>
                            <td><img class="thumb" src="{{ $g->cover_url }}" alt="" width="56" height="40" loading="lazy"></td>
                            <td class="judul">{{ $g->title }}</td>
                            <td>{{ $g->taken_at?->translatedFormat('j M Y') ?? '-' }}</td>
                            <td class="num">{{ $g->images_count }}</td>
                            <td><span @class(['badge', 'badge-ok' => $g->status === 'published', 'badge-warn' => $g->status !== 'published'])>{{ $g->status === 'published' ? 'Tayang' : 'Draft' }}</span></td>
                            <td class="aksi">
                                <a href="{{ $g->url }}" target="_blank" rel="noopener">Lihat</a>
                                <a href="{{ route('admin.galleries.edit', $g) }}">Kelola</a>
                                <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" data-confirm="Hapus album &quot;{{ $g->title }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $galleries->links() }}
    @else
        <p class="empty">Belum ada album.</p>
    @endif
@endsection
