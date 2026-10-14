<div class="form-grid form-grid-2">
    <x-admin.input label="Nama lengkap" name="name" :value="$official->name" :required="true" maxlength="150" />
    <x-admin.input label="Jabatan" name="position" :value="$official->position" :required="true" maxlength="150" help="Contoh: Kepala Urusan Keuangan." />

    <x-admin.select label="Kelompok" name="group" :options="$groups" :value="$official->group" blank="Pilih kelompok" :required="true" />
    <x-admin.input label="Telepon" name="phone" :value="$official->phone" maxlength="30" help="Opsional." />

    <x-admin.input label="Urutan tampil" name="sort_order" type="number" :value="$official->sort_order" min="0" help="Angka kecil tampil lebih dulu dalam kelompoknya." />
    <x-admin.check label="Tampilkan di website" name="is_active" :checked="$official->is_active" />

    <div style="grid-column:1/-1">
        <x-admin.image label="Foto" name="photo" :current="$official->photo ? $official->photo_url : null" help="Opsional. Tanpa foto, tampil inisial nama." />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.officials.index') }}">Batal</a>
</div>
