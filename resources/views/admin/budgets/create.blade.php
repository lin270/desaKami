@extends('layouts.admin')

@section('title', 'Unggah APBDes')

@section('content')
    <x-admin.page-title title="Unggah APBDes" :back="route('admin.budgets.index')" />

    <form method="POST" action="{{ route('admin.budgets.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.budgets._form')
    </form>
@endsection
