@extends('layouts.app')

@section('title', 'Kalender Warga')
@section('description', 'Kalender kegiatan dan agenda warga '.$site['site_name'].'.')

@section('content')
    <div class="page-head">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb"><ol><li><a href="{{ url('/') }}">Beranda</a></li><li aria-current="page">Kalender Warga</li></ol></nav>
            <p class="kl-eyebrow" style="color:var(--emas)">Ruang bersama warga</p>
            <h1>Kalender Warga</h1>
            <p class="lead">Lihat kegiatan, rencanakan waktu, dan tetap terhubung dengan aktivitas desa.</p>
        </div>
    </div>

    <div class="wide cal-page">
        <div class="cal-layout">
            {{-- ===== Kalender dinding ===== --}}
            <section class="cal" aria-label="Kalender kegiatan warga">
                <div class="cal-rings" aria-hidden="true"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                <header class="cal-head">
                    <div class="cal-title">
                        <span class="cal-year" id="cal-year"></span>
                        <h2 class="cal-month" id="cal-month">Kalender</h2>
                    </div>
                    <div class="cal-nav">
                        <button type="button" class="cal-btn" id="cal-prev" aria-label="Bulan sebelumnya">&#8249;</button>
                        <button type="button" class="cal-btn cal-today" id="cal-today">Hari ini</button>
                        <button type="button" class="cal-btn" id="cal-next" aria-label="Bulan berikutnya">&#8250;</button>
                    </div>
                </header>

                <div class="cal-dow" aria-hidden="true">
                    <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span class="we">Sab</span><span class="we">Min</span>
                </div>
                <div class="cal-grid" id="cal-grid" role="grid" aria-labelledby="cal-month"></div>
                <p class="cal-foot"><span class="cal-key"></span> Ada agenda &middot; pilih tanggal untuk melihat detail. Gunakan tombol panah untuk berpindah hari.</p>
            </section>

            {{-- ===== Panel tanggal terpilih ===== --}}
            <aside class="cal-side" aria-live="polite" aria-label="Agenda tanggal terpilih">
                <div class="cal-sheet">
                    <span class="cal-sheet-dow" id="sel-dow">&nbsp;</span>
                    <strong class="cal-sheet-day" id="sel-day">--</strong>
                    <span class="cal-sheet-my" id="sel-my"></span>
                </div>
                <div class="cal-sel-list" id="sel-list">
                    <p class="cal-none">Pilih tanggal pada kalender.</p>
                </div>
            </aside>
        </div>

        {{-- ===== Agenda mendatang (tampil juga tanpa JavaScript) ===== --}}
        <section class="cal-upcoming" aria-labelledby="mendatang-judul">
            <div class="kl-head">
                <div>
                    <p class="kl-eyebrow">Jangan lewatkan</p>
                    <h2 class="kl-h2" id="mendatang-judul" style="font-size:clamp(1.7rem,4vw,2.4rem)">Agenda mendatang</h2>
                </div>
            </div>
            @forelse ($upcoming as $event)
                <article class="cal-row">
                    <time class="cal-row-date" datetime="{{ $event->date->toDateString() }}">
                        <strong>{{ $event->date->format('d') }}</strong>
                        <span>{{ $event->date->translatedFormat('M Y') }}</span>
                    </time>
                    <div class="cal-row-body">
                        <h3>{{ $event->title }}</h3>
                        <p class="meta">
                            <span>{{ $event->date->translatedFormat('l') }}, {{ substr($event->start_time, 0, 5) }}@if ($event->end_time) - {{ substr($event->end_time, 0, 5) }}@endif WIB</span>
                            @if ($event->location)<span>{{ $event->location }}</span>@endif
                        </p>
                        @if ($event->description)<p>{{ \Illuminate\Support\Str::limit($event->description, 180) }}</p>@endif
                    </div>
                </article>
            @empty
                <p class="kosong">Belum ada agenda mendatang. Silakan cek kembali nanti.</p>
            @endforelse
        </section>
    </div>

    <script type="application/json" id="calendar-events">{!! json_encode($calendarEvents->map(fn ($e) => ['id' => $e->id, 'title' => $e->title, 'date' => $e->date->format('Y-m-d'), 'start' => substr($e->start_time, 0, 5), 'end' => $e->end_time ? substr($e->end_time, 0, 5) : null, 'location' => $e->location, 'description' => $e->description])->values(), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
@endsection

@push('scripts')
<script src="{{ asset('js/kalender.js') }}?v={{ filemtime(public_path('js/kalender.js')) }}" defer></script>
@endpush
