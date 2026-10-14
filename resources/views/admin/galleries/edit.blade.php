@extends('layouts.admin')

@section('title', 'Kelola Album')

@section('content')
    <x-admin.page-title title="Kelola Album" :back="route('admin.galleries.index')">
        <a class="btn btn-ghost" href="{{ $gallery->url }}" target="_blank" rel="noopener">Lihat di website</a>
    </x-admin.page-title>

    <form method="POST" action="{{ route('admin.galleries.update', $gallery) }}" enctype="multipart/form-data" class="form-card" style="margin-bottom:1.25rem">
        @csrf
        @method('PUT')
        @include('admin.galleries._form')
    </form>

    {{-- Daftar foto berada DI LUAR form utama karena tiap tombol punya form sendiri --}}
    <section class="card">
        <h2>Foto dalam album ({{ $gallery->images->count() }})</h2>

        @if ($gallery->images->isNotEmpty())
            <div class="foto-grid">
                @foreach ($gallery->images as $img)
                    <div @class(['foto-item', 'cover' => $gallery->cover_image_id === $img->id])>
                        <img src="{{ $img->image_url }}" alt="Foto {{ $loop->iteration }} album {{ $gallery->title }}" width="400" height="300" loading="lazy">
                        <div class="foto-info">
                            @if ($gallery->cover_image_id === $img->id)
                                <span class="badge badge-warn">&#9733; Cover</span>
                            @else
                                <form method="POST" action="{{ route('admin.galleries.cover', [$gallery, $img]) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn-link" type="submit">Jadikan cover</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.gallery-images.destroy', $img) }}" data-confirm="Hapus foto ini dari album?">
                                @csrf @method('DELETE')
                                <button class="btn-link danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="empty">Album ini belum berisi foto. Unggah foto lewat form di atas.</p>
        @endif
    </section>
@endsection
