@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <x-admin.page-title title="Pengaturan Website" />

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')

        <h2>Identitas</h2>
        <div class="form-grid form-grid-2">
            <x-admin.input label="Nama situs" name="site_name" :value="$settings['site_name'] ?? 'DESA KLEPU'" :required="true" maxlength="100" />
            <x-admin.input label="Subjudul" name="site_tagline" :value="$settings['site_tagline'] ?? 'Website Resmi Desa Klepu'" maxlength="150" />
            <div style="grid-column:1/-1">
                <x-admin.image label="Logo desa" name="logo" :current="\App\Support\Media::url($settings['logo'] ?? null, null)" help="Disarankan PNG/WebP persegi dengan latar transparan. Kosong = pakai lambang huruf M." />
            </div>
        </div>

        <h2 style="margin-top:1rem">Kontak</h2>
        <div class="form-grid form-grid-2">
            <div style="grid-column:1/-1">
                <x-admin.input label="Alamat kantor desa" name="address" :value="$settings['address'] ?? ''" maxlength="255" />
            </div>
            <x-admin.input label="Telepon" name="phone" :value="$settings['phone'] ?? ''" maxlength="50" />
            <x-admin.input label="Email" name="email" type="email" :value="$settings['email'] ?? ''" maxlength="150" />
            <div style="grid-column:1/-1">
                <x-admin.input label="Jam pelayanan" name="services_hours" :value="$settings['services_hours'] ?? ''" maxlength="150" help="Contoh: Senin - Jumat, 08.00 - 15.00 WIB." />
            </div>
        </div>

        <h2 style="margin-top:1rem">Sosial Media</h2>
        <div class="form-grid form-grid-3">
            <x-admin.input label="Facebook" name="facebook" type="text" :value="$settings['facebook'] ?? ''" maxlength="255" placeholder="https://facebook.com/..." />
            <x-admin.input label="Instagram" name="instagram" type="text" :value="$settings['instagram'] ?? ''" maxlength="255" placeholder="https://instagram.com/..." />
            <x-admin.input label="YouTube" name="youtube" type="text" :value="$settings['youtube'] ?? ''" maxlength="255" placeholder="https://youtube.com/..." />
        </div>

        <div class="form-actions">
            <button class="btn" type="submit">Simpan Pengaturan</button>
        </div>
    </form>
@endsection
