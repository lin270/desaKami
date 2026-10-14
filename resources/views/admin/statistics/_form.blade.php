<div class="form-grid form-grid-2">
    <x-admin.input label="Label" name="label" :value="$statistic->label" :required="true" maxlength="150" help="Contoh: Jumlah Penduduk." />
    <x-admin.input label="Nilai" name="value" :value="$statistic->value" :required="true" maxlength="100" help="Contoh: 4.500" />

    <x-admin.input label="Satuan" name="unit" :value="$statistic->unit" maxlength="30" help="Contoh: jiwa, KK, ha." />
    <x-admin.select label="Kelompok" name="group" :options="['penduduk' => 'Penduduk', 'wilayah' => 'Wilayah', 'lainnya' => 'Lainnya']" :value="$statistic->group" :required="true" />

    <x-admin.input label="Kunci" name="key" :value="$statistic->key" :required="true" maxlength="80" help="Pengenal unik: huruf kecil, angka, garis bawah. Contoh: penduduk_total." />
    <x-admin.input label="Urutan tampil" name="sort_order" type="number" :value="$statistic->sort_order" min="0" />
</div>

<div class="form-actions">
    <button class="btn" type="submit">Simpan</button>
    <a class="btn btn-ghost" href="{{ route('admin.statistics.index') }}">Batal</a>
</div>
