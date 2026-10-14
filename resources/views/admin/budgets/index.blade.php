@extends('layouts.admin')

@section('title', 'APBDes')

@section('content')
    <x-admin.page-title title="APBDes">
        <a class="btn" href="{{ route('admin.budgets.create') }}">+ Unggah APBDes</a>
    </x-admin.page-title>

    @if ($budgets->isNotEmpty())
        <div class="tbl-wrap">
            <table class="tbl">
                <thead><tr><th>Tahun</th><th>Judul</th><th>File</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($budgets as $b)
                        <tr>
                            <td class="judul">{{ $b->year }}</td>
                            <td>{{ $b->title ?: '-' }}</td>
                            <td>@if ($b->file)PDF @if ($b->file_size_label)({{ $b->file_size_label }})@endif @else - @endif</td>
                            <td><span @class(['badge', 'badge-ok' => $b->status === 'published', 'badge-warn' => $b->status !== 'published'])>{{ $b->status === 'published' ? 'Terbit' : 'Draft' }}</span></td>
                            <td class="aksi">
                                @if ($b->status === 'published' && $b->file)
                                    <a href="{{ $b->download_url }}">Unduh</a>
                                @endif
                                <a href="{{ route('admin.budgets.edit', $b) }}">Edit</a>
                                <form method="POST" action="{{ route('admin.budgets.destroy', $b) }}" data-confirm="Hapus APBDes {{ $b->year }} beserta file PDF-nya?">
                                    @csrf @method('DELETE')
                                    <button class="btn-link danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="empty">Belum ada APBDes. Klik "Unggah APBDes" untuk menambahkan PDF per tahun.</p>
    @endif
@endsection
