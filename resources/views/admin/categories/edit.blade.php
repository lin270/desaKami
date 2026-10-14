@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <x-admin.page-title title="Edit Kategori" :back="route('admin.categories.index')" />

    <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="form-card" style="max-width:560px">
        @csrf
        @method('PUT')
        <x-admin.input label="Nama" name="name" :value="$category->name" :required="true" maxlength="100" help="Slug tidak berubah saat nama diedit agar alamat kategori tetap sama." />
        <x-admin.input label="Deskripsi singkat" name="description" :value="$category->description" maxlength="255" />
        <div class="form-actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.categories.index') }}">Batal</a>
        </div>
    </form>
@endsection
