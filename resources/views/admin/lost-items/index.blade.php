@extends('layouts.admin')

@section('title', 'Barang Hilang & Ditemukan')

@section('content')
    <x-admin.page-title title="Barang Hilang & Ditemukan">
        <a class="btn" href="{{ route('admin.lost-items.create') }}">+ Tambah Laporan</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama barang..." aria-label="Cari barang">
        <select name="jenis" aria-label="Jenis" data-autosubmit>
            <option value="">Semua jenis</option>
            @foreach ($types as $key => $label)
                <option value="{{ $key }}" @selected($type === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($items->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Barang</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($items as $it)
                        <tr>
                            <td class="judul">{{ $it->title }}@if ($it->location)<small>{{ $it->location }}</small>@endif</td>
                            <td>{{ $types[$it->type] ?? $it->type }}</td>
                            <td>{{ $it->event_date?->translatedFormat('j M Y') ?? '-' }}</td>
                            <td>
                                <span @class(['badge', 'badge-ok' => $it->is_resolved, 'badge-warn' => ! $it->is_resolved])>{{ $it->is_resolved ? 'Selesai' : 'Aktif' }}</span>
                                @if ($it->status !== 'published')<span class="badge">Draft</span>@endif
                            </td>
                            <td class="aksi">
                                @if ($it->status === 'published')
                                    <a href="{{ $it->url }}" target="_blank" rel="noopener">Lihat</a>
                                @endif
                                <a href="{{ route('admin.lost-items.edit', $it) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.lost-items.resolve', $it) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn-link" type="submit">{{ $it->is_resolved ? 'Buka lagi' : 'Tandai selesai' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.lost-items.destroy', $it) }}" data-confirm="Hapus laporan &quot;{{ $it->title }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $items->links() }}
    @else
        <p class="empty">Belum ada laporan.</p>
    @endif
@endsection
