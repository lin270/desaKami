@props(['title', 'href' => null, 'linkText' => 'Lihat semua'])

<div class="section-title">
    <h2>{{ $title }}</h2>
    @if ($href)
        <a href="{{ $href }}">{{ $linkText }} &rarr;</a>
    @endif
</div>
