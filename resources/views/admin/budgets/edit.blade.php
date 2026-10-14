@extends('layouts.admin')

@section('title', 'Edit APBDes '.$budget->year)

@section('content')
    <x-admin.page-title title="Edit APBDes {{ $budget->year }}" :back="route('admin.budgets.index')" />

    <form method="POST" action="{{ route('admin.budgets.update', $budget) }}" enctype="multipart/form-data" class="form-card">
        @csrf @method('PUT')
        @include('admin.budgets._form')
    </form>
@endsection
