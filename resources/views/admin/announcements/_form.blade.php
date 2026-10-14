<div class="form-grid form-grid-2">
    <div style="grid-column:1/-1">
        <x-admin.input label="Judul" name="title" :value="$announcement->title" :required="true" maxlength="200" />
    </div>

    <x-admin.input label="Kategori" name="category" :value="$announcement->category" :required="true" maxlength="50" help="Contoh: Umum, Pelayanan, Kesehatan, Pembangunan." />

    @if ($announcement->exists)
        <x-admin.input label="Slug (URL)" name="slug" :value="$announcement->slug" maxlength="200" help="Mengubah slug mengubah alamat pengumuman." />
    @else
        <x-admin.input label="Slug (URL)" name="slug" maxlength="200" help="Kosongkan agar dibuat otomatis dari judul." />
    @endif

    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Terbit']" :value="$announcement->status" :required="true" />

    <x-admin.input label="Waktu terbit" name="published_at" type="datetime-local" :value="$announcement->published_at?->format('Y-m-d\TH:i')" help="Kosong = sekarang saat diterbitkan." />

    <div style="grid-column:1/-1">
        <x-admin.check label="Tandai sebagai pengumuman penting" name="is_important" :checked="$announcement->is_important" help="Pengumuman penting tampil paling atas." />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.image label="Gambar (opsional)" name="image" :current="$announcement->image ? $announcement->image_url : null" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Isi pengumuman" name="content" :value="$announcement->content" rows="10" :required="true" help="Mendukung Markdown. Tag HTML tidak diizinkan." />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.announcements.index') }}">Batal</a>
</div>
