<article class="pejabat">
    @if ($o->photo)
        <img class="pejabat-foto" src="{{ $o->photo_url }}" alt="Foto {{ $o->name }}" width="96" height="96" loading="lazy">
    @else
        <div class="avatar" aria-hidden="true">{{ $o->initials }}</div>
    @endif
    <h3>{{ $o->name }}</h3>
    <p class="jabatan">{{ $o->position }}</p>
    @if ($o->phone)
        <p class="telp"><a href="tel:{{ preg_replace('/[^0-9+]/', '', $o->phone) }}">{{ $o->phone }}</a></p>
    @endif
</article>
