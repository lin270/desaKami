@extends('layouts.admin')

@section('title', 'Edit Potensi')

@section('content')
    <x-admin.page-title title="Edit Potensi" :back="route('admin.potentials.index')" />

    <form method="POST" action="{{ route('admin.potentials.update', $potential) }}" enctype="multipart/form-data" class="form-card">
        @csrf @method('PUT')
        @include('admin.potentials._form')
    </form>
@endsection
