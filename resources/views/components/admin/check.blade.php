@props(['label', 'name', 'checked' => false, 'help' => null])

<div class="fg fg-check">
    <input type="hidden" name="{{ $name }}" value="0">
    <label>
        <input type="checkbox" name="{{ $name }}" value="1" @checked((bool) old($name, $checked))>
        {{ $label }}
    </label>
    @if ($help)<small class="help">{{ $help }}</small>@endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
