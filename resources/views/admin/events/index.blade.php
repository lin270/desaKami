@extends('layouts.admin')

@section('title', 'Agenda')

@section('content')
    <x-admin.page-title title="Agenda Desa">
        <a class="btn" href="{{ route('admin.events.create') }}">+ Tambah Agenda</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari nama kegiatan..." aria-label="Cari kegiatan">
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($events->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Kegiatan</th><th>Tanggal</th><th>Waktu</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($events as $e)
                        <tr>
                            <td class="judul">{{ $e->title }}</td>
                            <td>{{ $e->date->translatedFormat('j M Y') }}</td>
                            <td>{{ substr($e->start_time, 0, 5) }}@if ($e->end_time) - {{ substr($e->end_time, 0, 5) }}@endif</td>
                            <td>{{ $e->location }}</td>
                            <td><span @class(['badge', 'badge-ok' => $e->status === 'published', 'badge-warn' => $e->status !== 'published'])>{{ $e->status === 'published' ? 'Tayang' : 'Draft' }}</span></td>
                            <td class="aksi">
                                <a href="{{ route('admin.events.edit', $e) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.events.destroy', $e) }}" data-confirm="Hapus agenda &quot;{{ $e->title }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $events->links() }}
    @else
        <p class="empty">Belum ada agenda.</p>
    @endif
@endsection
