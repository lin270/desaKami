@extends('layouts.admin')

@section('title', 'Tambah Pengguna')

@section('content')
    <x-admin.page-title title="Tambah Pengguna" :back="route('admin.users.index')" />

    <form method="POST" action="{{ route('admin.users.store') }}" class="form-card" style="max-width:720px">
        @csrf
        @include('admin.users._form')
    </form>
@endsection
