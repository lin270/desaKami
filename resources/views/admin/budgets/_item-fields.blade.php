<div class="form-grid form-grid-2">
    <x-admin.select label="Jenis" name="type" :options="$types" :value="$item->type" :required="true" />
    <x-admin.input label="Kelompok / bidang" name="category" :value="$item->category" maxlength="150" help="Contoh: Pendapatan Asli Desa, Bidang Pembangunan Desa." />

    <div style="grid-column:1/-1">
        <x-admin.input label="Uraian" name="name" :value="$item->name" :required="true" maxlength="200" />
    </div>

    <x-admin.input label="Anggaran (Rp)" name="amount_plan" type="text" inputmode="numeric" :value="$item->amount_plan" :required="true" help="Angka saja, titik pemisah ribuan boleh." />
    <x-admin.input label="Realisasi (Rp)" name="amount_realized" type="text" inputmode="numeric" :value="$item->amount_realized" :required="true" />

    <x-admin.input label="Urutan" name="sort_order" type="number" :value="$item->sort_order" min="0" />
</div>
