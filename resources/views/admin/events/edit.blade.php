@extends('layouts.admin')

@section('title', 'Edit Agenda')

@section('content')
    <x-admin.page-title title="Edit Agenda" :back="route('admin.events.index')" />

    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.events._form')
    </form>
@endsection
