@extends('layouts.admin')

@section('title', 'Tambah Data Desa')

@section('content')
    <x-admin.page-title title="Tambah Data Desa" :back="route('admin.statistics.index')" />

    <form method="POST" action="{{ route('admin.statistics.store') }}" class="form-card" style="max-width:720px">
        @csrf
        @include('admin.statistics._form')
    </form>
@endsection
