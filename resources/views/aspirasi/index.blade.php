@extends('layouts.app')

@section('title', 'Ruang Aspirasi')
@section('description', 'Sampaikan kritik, saran, dan aspirasi Anda untuk '.$site['site_name'].'. Semua warga boleh menulis tanpa login.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <ol>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li aria-current="page">Ruang Aspirasi</li>
                </ol>
            </nav>
            <h1>Ruang Aspirasi</h1>
            <p class="lead">Papan terbuka untuk kritik, saran, dan harapan warga. Tulis langsung, tanpa perlu login.</p>
        </div>
    </div>

    <section class="bb-section" aria-labelledby="papan-judul">
        <div class="wide">
            <div class="bb" id="papan">
                <div class="bb-banner"><h2 id="papan-judul">Papan Aspirasi Warga</h2></div>

                @if ($aspirations->isNotEmpty())
                    <ul class="bb-notes">
                        @foreach ($aspirations as $a)
                            <li class="note c{{ $a->color % 6 }} t{{ $a->id % 5 }}">
                                <span class="pin" aria-hidden="true"></span>
                                <p class="note-text">{{ $a->message }}</p>
                                <p class="note-by"><span>&mdash; {{ $a->name }}</span><time datetime="{{ $a->created_at->toDateString() }}">{{ $a->created_at->translatedFormat('j M Y') }}</time></p>

                                @if ($a->reply)
                                    <div class="note-reply">
                                        <span class="clip" aria-hidden="true"></span>
                                        <p class="note-reply-label">Balasan {{ $site['site_name'] }}</p>
                                        <p class="note-reply-text">{{ $a->reply }}</p>
                                        @if ($a->replied_at)<time datetime="{{ $a->replied_at->toDateString() }}">{{ $a->replied_at->translatedFormat('j M Y') }}</time>@endif
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <div class="bb-pager">{{ $aspirations->fragment('papan')->links() }}</div>
                @else
                    <div class="bb-empty">
                        <div class="note c0 t1"><span class="pin" aria-hidden="true"></span>
                            <p class="note-text">Papan masih kosong. Jadilah yang pertama menuliskan aspirasi untuk desa kita.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bb-form-section" id="tulis" aria-labelledby="tulis-judul">
        <div class="container">
            <div class="bb-form">
                <p class="kl-eyebrow">Suara warga</p>
                <h2 id="tulis-judul">Tulis aspirasi Anda</h2>

                @if (session('success'))
                    <div class="bb-ok" role="status">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="bb-err" role="alert">
                        <strong>Aspirasi belum terkirim:</strong>
                        <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('aspirations.store') }}#tulis" novalidate>
                    @csrf
                    <div class="hp" aria-hidden="true">
                        <label for="website">Jangan diisi</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <label for="name">Nama <small>(opsional, kosongkan untuk "Warga")</small></label>
                    <input type="text" id="name" name="name" maxlength="40" value="{{ old('name') }}" autocomplete="nickname" @error('name') aria-invalid="true" @enderror>

                    <label for="message">Aspirasi, kritik, atau saran</label>
                    <textarea id="message" name="message" rows="5" maxlength="500" required minlength="10" placeholder="Tuliskan dengan sopan dan jelas..." @error('message') aria-invalid="true" @enderror>{{ old('message') }}</textarea>
                    <p class="bb-count"><span id="msg-count">0</span> / 500</p>

                    <div class="bb-rules">
                        <strong>Ketentuan:</strong> gunakan bahasa yang sopan. Dilarang memuat SARA, ujaran kebencian, hinaan pribadi, data pribadi orang lain, iklan, atau tautan. Aspirasi yang melanggar akan dihapus oleh admin.
                    </div>

                    <button type="submit" class="btn">Pasang di papan</button>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('js/aspirasi.js') }}?v={{ filemtime(public_path('js/aspirasi.js')) }}" defer></script>
@endpush
