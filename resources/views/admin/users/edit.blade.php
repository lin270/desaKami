@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('content')
    <x-admin.page-title title="Edit Pengguna" :back="route('admin.users.index')" />

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-card" style="max-width:720px">
        @csrf
        @method('PUT')
        @include('admin.users._form')
    </form>
@endsection
