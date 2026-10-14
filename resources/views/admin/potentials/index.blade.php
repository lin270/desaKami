@extends('layouts.admin')

@section('title', 'Potensi Desa')

@section('content')
    <x-admin.page-title title="Potensi Desa">
        <a class="btn" href="{{ route('admin.potentials.create') }}">+ Tambah Potensi</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul potensi..." aria-label="Cari judul">
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($potentials->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Judul</th><th>Kategori</th><th>Status</th><th class="num">Urutan</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($potentials as $p)
                        <tr>
                            <td class="judul">{{ $p->title }}@if ($p->location)<small>{{ $p->location }}</small>@endif</td>
                            <td>{{ $categories[$p->category] ?? $p->category }}</td>
                            <td><span @class(['badge', 'badge-ok' => $p->status === 'published', 'badge-warn' => $p->status !== 'published'])>{{ $p->status === 'published' ? 'Terbit' : 'Draft' }}</span></td>
                            <td class="num">{{ $p->sort_order }}</td>
                            <td class="aksi">
                                @if ($p->status === 'published')
                                    <a href="{{ $p->url }}" target="_blank" rel="noopener">Lihat</a>
                                @endif
                                <a href="{{ route('admin.potentials.edit', $p) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.potentials.destroy', $p) }}" data-confirm="Hapus potensi &quot;{{ $p->title }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $potentials->links() }}
    @else
        <p class="empty">Belum ada potensi desa.</p>
    @endif
@endsection
