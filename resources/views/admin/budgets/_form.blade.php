<div class="form-grid form-grid-2">
    <x-admin.input label="Tahun anggaran" name="year" type="number" :value="$budget->year" :required="true" min="2000" max="2100" />
    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Terbit']" :value="$budget->status" :required="true" help="Hanya yang Terbit dan punya file yang tampil di website." />

    <div style="grid-column:1/-1">
        <x-admin.input label="Judul" name="title" :value="$budget->title" maxlength="150" help="Opsional. Contoh: APBDes Desa Klepu Tahun 2026." />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.input label="File PDF" name="file" type="file" accept="application/pdf,.pdf" :help="$budget->file ? 'Sudah ada file ('.($budget->file_size_label ?? 'PDF').'). Pilih file baru hanya jika ingin menggantinya.' : 'Maksimal 8 MB, format PDF.'" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Catatan" name="notes" :value="$budget->notes" rows="3" maxlength="3000" help="Opsional. Contoh: dasar hukum (Perdes), tanggal penetapan." />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.budgets.index') }}">Batal</a>
</div>
