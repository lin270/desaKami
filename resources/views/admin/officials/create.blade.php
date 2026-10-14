@extends('layouts.admin')

@section('title', 'Tambah Perangkat Desa')

@section('content')
    <x-admin.page-title title="Tambah Perangkat Desa" :back="route('admin.officials.index')" />

    <form method="POST" action="{{ route('admin.officials.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.officials._form')
    </form>
@endsection
