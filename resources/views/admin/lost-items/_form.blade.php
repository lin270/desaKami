<div class="form-grid form-grid-2">
    <x-admin.select label="Jenis laporan" name="type" :options="$types" :value="$item->type" :required="true" />
    <x-admin.input label="Tanggal kejadian" name="event_date" type="date" :value="$item->event_date?->format('Y-m-d')" />

    <div style="grid-column:1/-1">
        <x-admin.input label="Nama barang" name="title" :value="$item->title" :required="true" maxlength="200" help="Contoh: Dompet cokelat, Kunci motor Honda." />
    </div>

    @if ($item->exists)
        <x-admin.input label="Slug (URL)" name="slug" :value="$item->slug" maxlength="200" />
    @else
        <x-admin.input label="Slug (URL)" name="slug" maxlength="200" help="Kosongkan agar dibuat otomatis." />
    @endif
    <x-admin.input label="Lokasi" name="location" :value="$item->location" maxlength="200" help="Tempat hilang / ditemukan." />

    <x-admin.input label="Kontak" name="contact" :value="$item->contact" maxlength="100" help="Nomor yang bisa dihubungi. Tampil untuk umum." />
    <x-admin.select label="Status tayang" name="status" :options="['draft' => 'Draft', 'published' => 'Tayang']" :value="$item->status" :required="true" />

    <div style="grid-column:1/-1">
        <x-admin.check label="Sudah selesai (barang kembali / pemilik ditemukan)" name="is_resolved" :checked="$item->is_resolved" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.image label="Foto barang (opsional)" name="image" :current="$item->image ? $item->image_url : null" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Ciri-ciri / keterangan" name="description" :value="$item->description" rows="6" :required="true" maxlength="3000" help="Jangan mencantumkan data pribadi sensitif (nomor KTP, alamat rumah lengkap)." />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.lost-items.index') }}">Batal</a>
</div>
