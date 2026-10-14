@props(['label', 'name', 'options' => [], 'value' => null, 'blank' => null, 'required' => false, 'help' => null])

<div class="fg">
    <label for="{{ $name }}">{{ $label }}@if ($required)<span class="req" aria-hidden="true"> *</span>@endif</label>
    <select id="{{ $name }}" name="{{ $name }}" @required($required) @error($name) aria-invalid="true" @enderror {{ $attributes }}>
        @if ($blank !== null)
            <option value="">{{ $blank }}</option>
        @endif
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected((string) old($name, $value) === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @if ($help)<small class="help">{{ $help }}</small>@endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
