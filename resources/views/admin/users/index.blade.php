@extends('layouts.admin')

@section('title', 'Pengguna')

@section('content')
    <x-admin.page-title title="Pengguna">
        <a class="btn" href="{{ route('admin.users.create') }}">+ Tambah Pengguna</a>
    </x-admin.page-title>

    <div class="tbl-wrap">
        <table class="tbl">
            <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th class="num">Berita</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td class="judul">{{ $u->name }}@if ($u->id === auth()->id())<small>Akun Anda</small>@endif</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge">{{ $u->role }}</span></td>
                        <td><span @class(['badge', 'badge-ok' => $u->is_active, 'badge-warn' => ! $u->is_active])>{{ $u->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="num">{{ $u->posts_count }}</td>
                        <td class="aksi">
                            <a href="{{ route('admin.users.edit', $u) }}">Edit</a>
                            @if ($u->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" data-confirm="Hapus pengguna {{ $u->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
