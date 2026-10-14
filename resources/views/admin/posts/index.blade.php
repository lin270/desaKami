@extends('layouts.admin')

@section('title', 'Berita')

@section('content')
    <x-admin.page-title title="Berita">
        <a class="btn" href="{{ route('admin.posts.create') }}">+ Tambah Berita</a>
    </x-admin.page-title>

    <form method="GET" class="filter" role="search">
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul berita..." aria-label="Cari judul">
        <select name="status" aria-label="Filter status" data-autosubmit>
            <option value="">Semua status</option>
            <option value="published" @selected($status === 'published')>Terbit</option>
            <option value="draft" @selected($status === 'draft')>Draft</option>
        </select>
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>

    @if ($posts->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead>
                    <tr><th></th><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Terbit</th><th class="num">Views</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td><img class="thumb" src="{{ $post->thumbnail_url }}" alt="" width="56" height="40" loading="lazy"></td>
                            <td class="judul">
                                {{ $post->title }}
                                @if ($post->is_featured)<small>Berita utama</small>@endif
                            </td>
                            <td>{{ $post->category->name }}</td>
                            <td>{{ $post->author->name }}</td>
                            <td><span @class(['badge', 'badge-ok' => $post->status === 'published', 'badge-warn' => $post->status !== 'published'])>{{ $post->status === 'published' ? 'Terbit' : 'Draft' }}</span></td>
                            <td>{{ $post->published_at?->translatedFormat('j M Y H:i') ?? '-' }}</td>
                            <td class="num">{{ number_format($post->views, 0, ',', '.') }}</td>
                            <td class="aksi">
                                <a href="{{ $post->url }}" target="_blank" rel="noopener">Lihat</a>
                                @can('update', $post)
                                    <a href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                                    <form method="POST" action="{{ route('admin.posts.toggle', $post) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn-link" type="submit">{{ $post->status === 'published' ? 'Batal terbit' : 'Terbitkan' }}</button>
                                    </form>
                                @endcan
                                @can('delete', $post)
                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" data-confirm="Hapus berita &quot;{{ $post->title }}&quot;?">
                                        @csrf @method('DELETE')
                                        <button class="btn-link danger" type="submit">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $posts->links() }}
    @else
        <p class="empty">Belum ada berita{{ $q !== '' ? ' yang cocok dengan pencarian' : '' }}.</p>
    @endif
@endsection
