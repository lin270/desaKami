@extends('layouts.admin')
@section('title', 'Aspirasi Warga')
@section('content')
<x-admin.page-title title="Aspirasi Warga" />
<p class="muted">Aspirasi dari halaman <a href="{{ route('aspirations.index') }}" target="_blank" rel="noopener">Ruang Aspirasi</a> langsung tampil di papan. Balas aspirasi agar balasan ikut dipasang, atau hapus bila melanggar ketentuan.</p>

<div class="asp-bar">
    <nav class="asp-tabs" aria-label="Filter status">
        <a href="{{ route('admin.aspirations.index', array_filter(['q' => $q])) }}" @class(['active' => $status === ''])>Semua ({{ $counts['all'] }})</a>
        <a href="{{ route('admin.aspirations.index', array_filter(['status' => 'pending', 'q' => $q])) }}" @class(['active' => $status === 'pending'])>Belum dibalas ({{ $counts['pending'] }})</a>
        <a href="{{ route('admin.aspirations.index', array_filter(['status' => 'replied', 'q' => $q])) }}" @class(['active' => $status === 'replied'])>Sudah dibalas ({{ $counts['replied'] }})</a>
    </nav>
    <form method="GET" class="filter" role="search">
        @if ($status !== '')<input type="hidden" name="status" value="{{ $status }}">@endif
        <label class="visually-hidden" for="q">Cari aspirasi</label>
        <input type="search" id="q" name="q" value="{{ $q }}" placeholder="Cari isi atau nama...">
        <button class="btn btn-ghost" type="submit">Cari</button>
    </form>
</div>

@forelse ($aspirations as $a)
    <article class="card asp-item" id="asp-{{ $a->id }}">
        <header class="asp-head">
            <div>
                <strong>{{ $a->name }}</strong>
                <span class="muted">&middot; {{ $a->created_at->translatedFormat('j F Y, H:i') }}</span>
            </div>
            <span class="badge {{ $a->reply ? 'badge-ok' : 'badge-warn' }}">{{ $a->reply ? 'Sudah dibalas' : 'Belum dibalas' }}</span>
        </header>
        <p class="asp-msg">{{ $a->message }}</p>

        <form method="POST" action="{{ route('admin.aspirations.reply', $a) }}" class="asp-reply">
            @csrf @method('PUT')
            <label for="reply-{{ $a->id }}">Balasan @if ($a->replied_at)<small class="muted">(oleh {{ $a->replier?->name ?? 'admin' }}, {{ $a->replied_at->translatedFormat('j M Y') }})</small>@endif</label>
            <textarea id="reply-{{ $a->id }}" name="reply" rows="3" maxlength="800" placeholder="Tulis balasan untuk warga...">{{ $a->reply }}</textarea>
            <div class="asp-actions">
                <button type="submit" class="btn">{{ $a->reply ? 'Perbarui balasan' : 'Kirim balasan' }}</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.aspirations.destroy', $a) }}" data-confirm="Hapus aspirasi ini dari papan? Gunakan hanya untuk isi yang melanggar ketentuan." class="asp-del">
            @csrf @method('DELETE')
            <button type="submit" class="btn-link danger">Hapus aspirasi</button>
        </form>
    </article>
@empty
    <p class="empty">Belum ada aspirasi{{ ($q !== '' || $status !== '') ? ' yang cocok dengan filter' : '' }}.</p>
@endforelse

{{ $aspirations->links() }}
@endsection
