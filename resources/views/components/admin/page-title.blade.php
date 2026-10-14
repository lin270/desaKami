@props(['title', 'back' => null])

<div class="page-title">
    <h1>{{ $title }}</h1>
    <div class="page-actions">
        @if ($back)<a class="btn btn-ghost" href="{{ $back }}">&larr; Kembali</a>@endif
        {{ $slot }}
    </div>
</div>
