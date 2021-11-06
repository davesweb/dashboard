<div class="mb-3">
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
    <input
        class="form-control @error($name) is-invalid @enderror"
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value($model ?? null, $locale) }}"
        placeholder="{{ $placeholder }}"
        @if($required) required="required" @endif
        @if($autofocus) autofocus="autofocus" @endif
    />
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>