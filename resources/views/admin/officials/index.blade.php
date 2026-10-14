@extends('layouts.admin')

@section('title', 'Pemerintahan Desa')

@section('content')
    <x-admin.page-title title="Pemerintahan Desa">
        <a class="btn" href="{{ route('admin.officials.create') }}">+ Tambah Perangkat</a>
    </x-admin.page-title>

    @if ($officials->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th></th><th>Nama</th><th>Jabatan</th><th>Kelompok</th><th class="num">Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($officials as $o)
                        <tr>
                            <td>
                                @if ($o->photo)
                                    <img class="avatar-sm" src="{{ $o->photo_url }}" alt="" width="40" height="40" loading="lazy">
                                @else
                                    <span class="avatar-sm" aria-hidden="true">{{ $o->initials }}</span>
                                @endif
                            </td>
                            <td class="judul">{{ $o->name }}</td>
                            <td>{{ $o->position }}</td>
                            <td>{{ $groups[$o->group] ?? $o->group }}</td>
                            <td class="num">{{ $o->sort_order }}</td>
                            <td><span @class(['badge', 'badge-ok' => $o->is_active])>{{ $o->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td class="aksi">
                                <a href="{{ route('admin.officials.edit', $o) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.officials.destroy', $o) }}" data-confirm="Hapus {{ $o->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="empty">Belum ada data perangkat desa.</p>
    @endif
@endsection
