@extends('layouts.admin')

@section('title', 'Tambah Berita')

@section('content')
    <x-admin.page-title title="Tambah Berita" :back="route('admin.posts.index')" />

    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @include('admin.posts._form')
    </form>
@endsection
