@extends('layouts.admin')

@section('title', 'Edit Rincian')

@section('content')
    <x-admin.page-title title="Edit Rincian APBDes {{ $budget->year }}" :back="route('admin.budgets.edit', $budget)" />

    <form method="POST" action="{{ route('admin.budget-items.update', $item) }}" class="form-card">
        @csrf @method('PUT')
        @include('admin.budgets._item-fields')
        <div class="form-actions">
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="{{ route('admin.budgets.edit', $budget) }}">Batal</a>
        </div>
    </form>
@endsection
