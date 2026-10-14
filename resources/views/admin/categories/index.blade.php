@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <x-admin.page-title title="Kategori Berita" />

    <div class="two-col">
        <div>
            @if ($categories->isNotEmpty())
                <div class="tbl-wrap">
                    <table class="tbl">
                        <thead><tr><th>Nama</th><th>Slug</th><th class="num">Jumlah berita</th><th>Aksi</th></tr></thead>
                        <tbody>
                            @foreach ($categories as $c)
                                <tr>
                                    <td class="judul">{{ $c->name }}@if ($c->description)<small>{{ $c->description }}</small>@endif</td>
                                    <td>{{ $c->slug }}</td>
                                    <td class="num">{{ $c->posts_count }}</td>
                                    <td class="aksi">
                                        <a href="{{ route('admin.categories.edit', $c) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" data-confirm="Hapus kategori &quot;{{ $c->name }}&quot;?">
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
                <p class="empty">Belum ada kategori.</p>
            @endif
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" class="form-card" style="align-self:start">
            @csrf
            <h2>Tambah Kategori</h2>
            <x-admin.input label="Nama" name="name" :required="true" maxlength="100" />
            <x-admin.input label="Deskripsi singkat" name="description" maxlength="255" />
            <div class="form-actions"><button class="btn" type="submit">Tambah</button></div>
        </form>
    </div>
@endsection
