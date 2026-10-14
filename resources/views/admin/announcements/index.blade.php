@extends('layouts.admin')

@section('title', 'Pengumuman')

@section('content')
    <x-admin.page-title title="Pengumuman">
        <a class="btn" href="{{ route('admin.announcements.create') }}">+ Tambah Pengumuman</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul pengumuman..." aria-label="Cari judul">
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($announcements->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th>Terbit</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($announcements as $a)
                        <tr>
                            <td class="judul">
                                {{ $a->title }}
                                @if ($a->is_important)<small>&#9733; Penting</small>@endif
                            </td>
                            <td>{{ $a->category }}</td>
                            <td><span @class(['badge', 'badge-ok' => $a->status === 'published', 'badge-warn' => $a->status !== 'published'])>{{ $a->status === 'published' ? 'Terbit' : 'Draft' }}</span></td>
                            <td>{{ $a->published_at?->translatedFormat('j M Y H:i') ?? '-' }}</td>
                            <td class="aksi">
                                <a href="{{ $a->url }}" target="_blank" rel="noopener">Lihat</a>
                                <a href="{{ route('admin.announcements.edit', $a) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.announcements.destroy', $a) }}" data-confirm="Hapus pengumuman &quot;{{ $a->title }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $announcements->links() }}
    @else
        <p class="empty">Belum ada pengumuman.</p>
    @endif
@endsection
