{{-- Satu kotak pada bagan struktur organisasi --}}
<article @class(['org-node', 'org-node-top' => ($top ?? false)])>
    @if ($o->photo)
        <img class="org-photo" src="{{ $o->photo_url }}" alt="Foto {{ $o->name }}" width="64" height="64" loading="lazy">
    @else
        <span class="org-avatar" aria-hidden="true">{{ $o->initials }}</span>
    @endif
    <div class="org-text">
        <h3>{{ $o->name }}</h3>
        <p>{{ $o->position }}</p>
        @if ($o->phone)
            <a class="org-tel" href="tel:{{ preg_replace('/[^0-9+]/', '', $o->phone) }}">{{ $o->phone }}</a>
        @endif
    </div>
</article>
