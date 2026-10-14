@props(['label', 'name', 'type' => 'text', 'value' => null, 'help' => null, 'required' => false])

<div class="fg">
    <label for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true"> *</span>@endif</label>
    <input
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        @if ($type !== 'password') value="{{ old($name, $value) }}" @endif
        @required($required)
        @error($name) aria-invalid="true" @enderror
        {{ $attributes }}
    >
    @if ($help)<small class="help">{{ $help }}</small>@endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
