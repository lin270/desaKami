@extends('layouts.admin')

@section('title', 'Edit Berita')

@section('content')
    <x-admin.page-title title="Edit Berita" :back="route('admin.posts.index')">
        <a class="btn btn-ghost" href="{{ $post->url }}" target="_blank" rel="noopener">Lihat di website</a>
    </x-admin.page-title>

    <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data" class="form-card">
        @csrf
        @method('PUT')
        @include('admin.posts._form')
    </form>
@endsection
