@props(['post', 'detail' => false])

<article class="card">
    <a href="{{ $post->url }}" class="card-img" tabindex="-1" aria-hidden="true">
        <img src="{{ $post->thumbnail_url }}" alt="" width="600" height="375" loading="lazy">
    </a>
    <div class="card-body">
        <a class="kategori" href="{{ $post->category->url }}">{{ $post->category->name }}</a>
        <h3 class="card-title"><a href="{{ $post->url }}">{{ $post->title }}</a></h3>
        <p class="card-excerpt">{{ $post->excerpt_text }}</p>
        <p class="meta">
            <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('j F Y') }}</time>
            @if ($detail)
                <span>{{ $post->author->name }}</span>
                <span>{{ number_format($post->views, 0, ',', '.') }} dibaca</span>
            @endif
        </p>
    </div>
</article>
