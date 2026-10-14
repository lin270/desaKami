@extends('layouts.admin')

@section('title', 'Buat Album')

@section('content')
    <x-admin.page-title title="Buat Album" :back="route('admin.galleries.index')" />

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.galleries._form')
    </form>
@endsection
