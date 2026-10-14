@extends('layouts.admin')

@section('title', 'Data Desa')

@section('content')
    <x-admin.page-title title="Data Desa">
        <a class="btn" href="{{ route('admin.statistics.create') }}">+ Tambah Data</a>
    </x-admin.page-title>

    <p class="muted">Data ini tampil di beranda dan halaman Profil. Angka ditulis apa adanya (misalnya <code>4.500</code>).</p>

    @php $judulGrup = ['penduduk' => 'Penduduk', 'wilayah' => 'Wilayah', 'lainnya' => 'Lainnya']; @endphp

    @forelse ($statistics as $group => $items)
        <h2 style="margin:1.25rem 0 .6rem;font-size:1.05rem">{{ $judulGrup[$group] ?? $group }}</h2>
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Label</th><th>Nilai</th><th>Satuan</th><th>Kunci</th><th class="num">Urutan</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($items as $s)
                        <tr>
                            <td class="judul">{{ $s->label }}</td>
                            <td>{{ $s->value }}</td>
                            <td>{{ $s->unit ?? '-' }}</td>
                            <td><code>{{ $s->key }}</code></td>
                            <td class="num">{{ $s->sort_order }}</td>
                            <td class="aksi">
                                <a href="{{ route('admin.statistics.edit', $s) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.statistics.destroy', $s) }}" data-confirm="Hapus data &quot;{{ $s->label }}&quot;?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p class="empty">Belum ada data desa.</p>
    @endforelse
@endsection
