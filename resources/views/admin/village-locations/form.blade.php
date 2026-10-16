@extends('layouts.admin')
@section('title', $location->exists ? 'Edit Lokasi Peta' : 'Tambah Lokasi Peta')
@section('content')
<x-admin.page-title :title="$location->exists ? 'Edit Lokasi Peta' : 'Tambah Lokasi Peta'" />
@if ($errors->any())
<div class="alert alert-danger" role="alert" style="margin-bottom:1rem">
    <strong>Data belum tersimpan.</strong>
    <ul style="margin:.4rem 0 0;padding-left:1.2rem">
        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
</div>
@endif
<form method="POST" action="{{ $location->exists ? route('admin.village-locations.update',$location) : route('admin.village-locations.store') }}" class="form-card" enctype="multipart/form-data">@csrf @if($location->exists) @method('PUT') @endif
<div class="form-grid form-grid-2"><x-admin.input label="Nama lokasi" name="name" :value="$location->name" required maxlength="150"/><x-admin.input label="Kategori" name="category" :value="$location->category" required maxlength="60" help="Contoh: Pemerintahan, Pendidikan, Ibadah, Kesehatan, Fasilitas Umum."/><div style="grid-column:1/-1"><x-admin.input label="Alamat atau keterangan lokasi" name="address" :value="$location->address" maxlength="255"/></div><x-admin.input label="Latitude" name="latitude" type="number" :value="$location->latitude" required step="any" help="Ambil koordinat dari lokasi yang benar; rentang -90 sampai 90."/><x-admin.input label="Longitude" name="longitude" type="number" :value="$location->longitude" required step="any" help="Rentang -180 sampai 180."/><div style="grid-column:1/-1"><x-admin.textarea label="Deskripsi" name="description" :value="$location->description" rows="4" maxlength="1500"/></div><div style="grid-column:1/-1"><x-admin.image label="Foto lokasi" name="image" :current="$location->image_url" help="Opsional. Tampil di peta dan daftar lokasi. JPG, PNG, atau WebP, maksimal 4 MB."/>@if($location->image)<label style="display:flex;align-items:center;gap:.5rem;font-size:.88rem"><input type="checkbox" name="remove_image" value="1"> Hapus foto saat ini</label>@endif</div><label class="publish-check" style="display:flex;align-items:center;gap:.6rem;grid-column:1/-1;"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$location->is_published))> Tampilkan di halaman Peta Desa</label></div><div class="form-actions"><button class="btn" type="submit">Simpan Lokasi</button><a class="btn btn-ghost" href="{{ route('admin.village-locations.index') }}">Batal</a></div></form>
@endsection
