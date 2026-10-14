<div class="form-grid form-grid-2">
    <div style="grid-column:1/-1">
        <x-admin.input label="Judul album" name="title" :value="$gallery->title" :required="true" maxlength="200" />
    </div>

    <x-admin.input label="Tanggal dokumentasi" name="taken_at" type="date" :value="$gallery->taken_at?->format('Y-m-d')" />
    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Tayang']" :value="$gallery->status" :required="true" />

    @if ($gallery->exists)
        <x-admin.input label="Slug" name="slug" :value="$gallery->slug" maxlength="200" />
    @else
        <x-admin.input label="Slug" name="slug" maxlength="200" help="Kosongkan agar dibuat otomatis." />
    @endif

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Deskripsi" name="description" :value="$gallery->description" rows="3" maxlength="1000" />
    </div>

    <div class="fg" style="grid-column:1/-1">
        <label for="images">{{ $gallery->exists ? 'Tambah foto' : 'Foto' }}</label>
        <input type="file" id="images" name="images[]" accept="image/jpeg,image/png,image/webp" multiple @error('images') aria-invalid="true" @enderror @error('images.*') aria-invalid="true" @enderror>
        <small class="help">Boleh memilih banyak foto sekaligus (maksimal 20 per unggahan, 4 MB per foto). JPG, PNG, atau WebP.</small>
        @error('images')<span class="err">{{ $message }}</span>@enderror
        @foreach ($errors->get('images.*') as $messages)
            @foreach ($messages as $message)<span class="err">{{ $message }}</span>@endforeach
        @endforeach
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.galleries.index') }}">{{ $gallery->exists ? 'Selesai' : 'Batal' }}</a>
</div>
