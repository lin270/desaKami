@extends('layouts.admin')

@section('title', 'Tambah Agenda')

@section('content')
    <x-admin.page-title title="Tambah Agenda" :back="route('admin.events.index')" />

    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.events._form')
    </form>
@endsection
