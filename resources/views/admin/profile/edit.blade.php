@extends('layouts.admin')

@section('title', 'Profil Desa')

@section('content')
    <x-admin.page-title title="Profil Desa">
        <a class="btn btn-ghost" href="{{ url('/profil') }}" target="_blank" rel="noopener">Lihat di website</a>
    </x-admin.page-title>

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')

        <x-admin.image label="Gambar sampul (opsional)" name="hero_image" :current="$profile->hero_image ? $profile->hero_image_url : null" />

        <x-admin.textarea label="Tentang Desa" name="about" :value="$profile->about" rows="8" help="Mendukung Markdown." />
        <x-admin.textarea label="Sejarah Desa" name="history" :value="$profile->history" rows="8" help="Isi dengan sejarah resmi desa. Mendukung Markdown." />
        <x-admin.textarea label="Visi" name="vision" :value="$profile->vision" rows="3" />
        <x-admin.textarea label="Misi" name="mission" :value="$profile->mission" rows="6" help="Satu poin misi per baris." />
        <x-admin.textarea label="Deskripsi Wilayah" name="area_description" :value="$profile->area_description" rows="4" help="Angka luas, RT/RW, dan dusun diatur di menu Data Desa." />
        <x-admin.textarea label="Informasi Umum" name="general_info" :value="$profile->general_info" rows="6" help="Mendukung Markdown." />

        <div class="form-actions">
            <button class="btn" type="submit">Simpan Profil</button>
        </div>
    </form>
@endsection
