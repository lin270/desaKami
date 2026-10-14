@props(['label', 'name', 'value' => null, 'rows' => 5, 'help' => null, 'required' => false])

<div class="fg">
    <label for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true"> *</span>@endif</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @required($required)
        @error($name) aria-invalid="true" @enderror
        {{ $attributes }}
    >{{ old($name, $value) }}</textarea>
    @if ($help)<small class="help">{{ $help }}</small>@endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
