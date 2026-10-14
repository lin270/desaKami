@extends('layouts.admin')

@section('title', 'Edit Pengumuman')

@section('content')
    <x-admin.page-title title="Edit Pengumuman" :back="route('admin.announcements.index')" />

    <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.announcements._form')
    </form>
@endsection
