@extends('layouts.admin')

@section('title', 'Tambah Laporan')

@section('content')
    <x-admin.page-title title="Tambah Laporan" :back="route('admin.lost-items.index')" />

    <form method="POST" action="{{ route('admin.lost-items.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf 
        @include('admin.lost-items._form')
    </form>
@endsection
