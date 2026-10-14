@extends('layouts.admin')

@section('title', 'Tambah Potensi')

@section('content')
    <x-admin.page-title title="Tambah Potensi" :back="route('admin.potentials.index')" />

    <form method="POST" action="{{ route('admin.potentials.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.potentials._form')
    </form>
@endsection
