@props(['label', 'name', 'current' => null, 'help' => 'Format JPG, PNG, atau WebP. Maksimal 4 MB.'])

<div class="fg">
    <label for="{{ $name }}">{{ $label }}</label>
    <div class="img-field">
        <img id="preview-{{ $name }}" class="img-preview" src="{{ $current }}" alt="Pratinjau {{ $label }}" @unless ($current) hidden @endunless>
        <input type="file" id="{{ $name }}" name="{{ $name }}" accept="image/jpeg,image/png,image/webp" data-preview="preview-{{ $name }}" @error($name) aria-invalid="true" @enderror>
    </div>
    @if ($help)<small class="help">{{ $help }}</small>@endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
