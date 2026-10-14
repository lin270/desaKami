@props(['event', 'past' => false])

<article @class(['agenda-kartu', 'lalu' => $past])>
    <time class="tanggal" datetime="{{ $event->date->toDateString() }}">
        <span class="hari">{{ $event->date->format('d') }}</span>
        <span class="bulan">{{ $event->date->translatedFormat('M Y') }}</span>
    </time>
    <div>
        <h3>{{ $event->title }}</h3>
        <p class="meta">
            <span>{{ $event->date->translatedFormat('l, j F Y') }}</span>
            <span>{{ substr($event->start_time, 0, 5) }}@if ($event->end_time) - {{ substr($event->end_time, 0, 5) }}@endif WIB</span>
            <span>{{ $event->location }}</span>
        </p>
        @if ($event->description)
            <p class="agenda-desc">{{ $event->description }}</p>
        @endif
    </div>
</article>
