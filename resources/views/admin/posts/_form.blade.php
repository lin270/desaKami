<div class="form-grid form-grid-2">
    <div style="grid-column:1/-1">
        <x-admin.input label="Judul" name="title" :value="$post->title" :required="true" maxlength="200" />
    </div>

    <x-admin.select label="Kategori" name="category_id" :options="$categories->pluck('name', 'id')->all()" :value="$post->category_id" blank="Pilih kategori" :required="true" />

    @if ($isAdmin)
        <x-admin.select label="Penulis" name="author_id" :options="$authors->pluck('name', 'id')->all()" :value="$post->author_id ?? auth()->id()" :required="true" />
    @else
        <div class="fg">
            <label>Penulis</label>
            <input type="text" value="{{ $post->exists ? $post->author->name : auth()->user()->name }}" disabled>
            <small class="help">Penulis otomatis sesuai akun Anda.</small>
        </div>
    @endif

    @if ($post->exists)
        <x-admin.input label="Slug (URL)" name="slug" :value="$post->slug" help="Huruf kecil, angka, dan tanda hubung. Mengubah slug mengubah alamat berita." maxlength="200" />
    @else
        <x-admin.input label="Slug (URL)" name="slug" help="Kosongkan agar dibuat otomatis dari judul." maxlength="200" />
    @endif

    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Terbit']" :value="$post->status" :required="true" />

    <x-admin.input label="Waktu terbit" name="published_at" type="datetime-local" :value="$post->published_at?->format('Y-m-d\TH:i')" help="Isi waktu di masa depan untuk menjadwalkan. Kosong = sekarang saat diterbitkan." />

    <x-admin.check label="Jadikan berita utama (headline beranda)" name="is_featured" :checked="$post->is_featured" />

    <div style="grid-column:1/-1">
        <x-admin.image label="Gambar utama" name="thumbnail" :current="$post->thumbnail ? $post->thumbnail_url : null" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Ringkasan (excerpt)" name="excerpt" :value="$post->excerpt" rows="3" maxlength="300" help="Maksimal 300 karakter. Kosong = diambil otomatis dari awal isi berita." />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Isi berita" name="content" :value="$post->content" rows="16" :required="true" help="Mendukung Markdown: ## Judul, **tebal**, *miring*, - daftar, [teks](https://tautan). Tag HTML tidak diizinkan." />
    </div>
</div>

@if ($post->exists)
    <p class="muted">Dibaca {{ number_format($post->views, 0, ',', '.') }} kali &middot; dibuat {{ $post->created_at->translatedFormat('j F Y H:i') }}</p>
@endif

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.posts.index') }}">Batal</a>
</div>
