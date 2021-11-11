<input
    class="form-control @error($errorKey) is-invalid @enderror"
    type="{{ $type ?? 'text' }}"
    name="{{ $name }}"
    id="{{ $id }}"
    value="{{ $value($model ?? null, $locale) }}"
    placeholder="{{ $placeholder }}"
    @if($required) required="required" @endif
    @if($autofocus) autofocus="autofocus" @endif
    @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
/>
@error($errorKey)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror