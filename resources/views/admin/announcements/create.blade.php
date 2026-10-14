@extends('layouts.admin')

@section('title', 'Tambah Pengumuman')

@section('content')
    <x-admin.page-title title="Tambah Pengumuman" :back="route('admin.announcements.index')" />

    <form method="POST" action="{{ route('admin.announcements.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.announcements._form')
    </form>
@endsection
