@extends('layouts.admin')

@section('title', 'Edit Perangkat Desa')

@section('content')
    <x-admin.page-title title="Edit Perangkat Desa" :back="route('admin.officials.index')" />

    <form method="POST" action="{{ route('admin.officials.update', $official) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.officials._form')
    </form>
@endsection
