@extends('layouts.admin')

@section('title', 'Edit Laporan')

@section('content')
    <x-admin.page-title title="Edit Laporan" :back="route('admin.lost-items.index')" />

    <form method="POST" action="{{ route('admin.lost-items.update', $item) }}" enctype="multipart/form-data" class="form-card">
        @csrf @method('PUT')
        @include('admin.lost-items._form')
    </form>
@endsection
