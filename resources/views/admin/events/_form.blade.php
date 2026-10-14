<div class="form-grid form-grid-2">
    <div style="grid-column:1/-1">
        <x-admin.input label="Nama kegiatan" name="title" :value="$event->title" :required="true" maxlength="200" />
    </div>

    <x-admin.input label="Tanggal" name="date" type="date" :value="$event->date?->format('Y-m-d')" :required="true" />
    <x-admin.input label="Lokasi" name="location" :value="$event->location" :required="true" maxlength="255" />

    <x-admin.input label="Jam mulai" name="start_time" type="time" :value="$event->start_time ? substr($event->start_time, 0, 5) : null" :required="true" />
    <x-admin.input label="Jam selesai" name="end_time" type="time" :value="$event->end_time ? substr($event->end_time, 0, 5) : null" help="Opsional." />

    @if ($event->exists)
        <x-admin.input label="Slug" name="slug" :value="$event->slug" maxlength="200" />
    @else
        <x-admin.input label="Slug" name="slug" maxlength="200" help="Kosongkan agar dibuat otomatis." />
    @endif

    <x-admin.select label="Status" name="status" :options="['draft' => 'Draft', 'published' => 'Tayang']" :value="$event->status" :required="true" />

    <div style="grid-column:1/-1">
        <x-admin.textarea label="Deskripsi" name="description" :value="$event->description" rows="5" maxlength="2000" />
    </div>

    <div style="grid-column:1/-1">
        <x-admin.image label="Gambar (opsional)" name="image" :current="$event->image ? $event->image_url : null" />
    </div>
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.events.index') }}">Batal</a>
</div>
