@extends('layouts.app')

@section('title', 'Peta Desa')
@section('description', 'Jelajahi lokasi dan fasilitas di '.$site['site_name'].' melalui peta interaktif lengkap dengan foto.')
@section('body_class', 'page-map')

@section('content')
    <h1 class="visually-hidden">Peta Desa Klepu</h1>

    <div class="gm" id="gm">
        <div id="village-map" role="application" aria-label="Peta interaktif {{ $site['site_name'] }}"></div>

        <div class="gm-state" id="map-state" role="status" @if ($locations->isNotEmpty()) hidden @endif>
            <div>
                @if ($locations->isEmpty())
                    <strong>Belum ada lokasi</strong>
                    Lokasi dan koordinat belum ditambahkan oleh pengelola desa.
                @else
                    <strong>Memuat peta&hellip;</strong>
                @endif
            </div>
        </div>

        <aside class="gm-panel" id="gm-panel" aria-label="Pencarian dan daftar lokasi">
            <button type="button" class="gm-handle" id="gm-handle" aria-expanded="false" aria-controls="gm-scroll" aria-label="Perluas daftar lokasi"><span></span></button>

            <div class="gm-top">
                <label class="gm-search">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                    <input id="location-search" type="search" placeholder="Cari tempat di Desa Klepu" aria-label="Cari lokasi" autocomplete="off">
                    <button type="button" class="gm-clear" id="gm-clear" aria-label="Hapus pencarian" hidden>&times;</button>
                </label>

                <div class="gm-chips" id="gm-chips" role="group" aria-label="Filter kategori">
                    <button type="button" class="gm-chip is-on" data-category="">Semua</button>
                    @foreach ($categories as $category)
                        <button type="button" class="gm-chip" data-category="{{ $category }}">{{ $category }}</button>
                    @endforeach
                </div>
            </div>

            <div class="gm-scroll" id="gm-scroll">
                {{-- Daftar --}}
                <div id="gm-list-view">
                    <p class="gm-count"><span id="location-count">{{ $locations->count() }}</span> tempat</p>
                    <ul class="gm-list" id="location-list">
                        @foreach ($locations as $location)
                            <li>
                                <button type="button" class="location-item"
                                        data-id="{{ $location->id }}"
                                        data-name="{{ $location->name }}"
                                        data-category="{{ $location->category }}"
                                        data-lat="{{ $location->latitude }}"
                                        data-lng="{{ $location->longitude }}"
                                        data-description="{{ $location->description }}"
                                        data-address="{{ $location->address }}"
                                        data-image="{{ $location->image_url }}">
                                    <span class="gm-thumb">
                                        @if ($location->image_url)
                                            <img src="{{ $location->image_url }}" alt="" width="84" height="84" loading="lazy">
                                        @else
                                            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 21s7-6.2 7-11.5A7 7 0 0 0 5 9.5C5 14.8 12 21 12 21Z"/><circle cx="12" cy="9.5" r="2.5"/></svg>
                                        @endif
                                    </span>
                                    <span class="gm-item-copy">
                                        <strong>{{ $location->name }}</strong>
                                        <small>{{ $location->category }}</small>
                                        @if ($location->address)<small class="gm-addr">{{ $location->address }}</small>@endif
                                    </span>
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    @if ($locations->isEmpty())
                        <div class="gm-empty">
                            <strong>Lokasi segera hadir</strong>
                            <p>Admin dapat menambahkan lokasi, foto, dan koordinat melalui dashboard.</p>
                        </div>
                    @endif
                    <div class="gm-empty is-hidden" id="location-none">
                        <strong>Tidak ada hasil</strong>
                        <p>Coba kata kunci atau kategori lain.</p>
                    </div>
                    <p class="gm-foot">Lokasi berasal dari data yang diverifikasi pengelola desa. Peta &copy; kontributor OpenStreetMap.</p>
                </div>
            </div>
        </aside>

        <button type="button" class="gm-reset" id="map-reset" hidden>Tampilkan semua</button>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/peta.js') }}?v={{ filemtime(public_path('js/peta.js')) }}" defer></script>
@endpush
