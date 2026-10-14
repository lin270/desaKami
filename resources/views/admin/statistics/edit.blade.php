@extends('layouts.admin')

@section('title', 'Edit Data Desa')

@section('content')
    <x-admin.page-title title="Edit Data Desa" :back="route('admin.statistics.index')" />

    <form method="POST" action="{{ route('admin.statistics.update', $statistic) }}" class="form-card" style="max-width:720px">
        @csrf
        @method('PUT')
        @include('admin.statistics._form')
    </form>
@endsection
