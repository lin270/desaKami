<div class="form-grid form-grid-2">
    <div style="grid-column:1/-1">
        <x-admin.input label="Judul" name="title" :value="$potential->title" :required="true" maxlength="200" />
    </div>

    <x-admin.select label="Kategori" name="category" :options="$categories" :value="$potential->category" :required="true" />

    @if ($potential->exists)
        <x-admin.input label="Slug (URL)" name="slug" :value="$potential->slug" maxlength="200" help="Mengubah slug mengubah alamat halaman." />
    @else
        <x-admin.input label="Slug (URL)" name="slug" maxlength="200" help="Kosongkan agar dibuat otomatis dari judul." />
    @endif

    <x-admin.input label="Lokasi" name="location" :value="$potential->location" maxlength="200" help="Opsional. Contoh: Dusun Krajan." />
    <x-admin.input label="Kontak" name="contact" :value="$potential->contact" maxlength="100" help="Opsional, tampil untuk umum. Contoh: 0812-xxxx-xxxx." />

    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Terbit']" :value="$potential->status" :required="true" />
    <x-admin.input label="Urutan tampil" name="sort_order" type="number" :value="$potential->sort_order" min="0" help="Angka kecil tampil lebih dulu." />

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Ringkasan" name="summary" :value="$potential->summary" rows="2" maxlength="300" help="Opsional, maks. 300 karakter. Kosong = diambil dari isi." />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.image label="Gambar" name="image" :current="$potential->image ? $potential->image_url : null" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Isi" name="content" :value="$potential->content" rows="10" :required="true" help="Mendukung Markdown. Tag HTML tidak diizinkan." />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.potentials.index') }}">Batal</a>
</div>
