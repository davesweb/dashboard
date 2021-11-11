<select
        class="form-control @error($errorKey) is-invalid @enderror"
        type="{{ $type ?? 'text' }}"
        name="{{ $name }}{{ $multiple ? '[]' : '' }}"
        id="{{ $id }}"
        @if($required) required="required" @endif
        @if($autofocus) autofocus="autofocus" @endif
        @if($ariaLabel) aria-label="{{ $ariaLabel }}" @endif
        @if($multiple) multiple="multiple" @endif
>
    @foreach($options as $optionKey => $optionValue)
        <option value="{{ $optionKey }}" {{ $value($model ?? null, $locale) === $optionKey ? ' selected="selected"' : '' }}>{{ $optionValue }}</option>
    @endforeach
</select>
@if($info)
    <div id="{{ $id }}-help" class="form-text">{{ $info }}</div>
@endif
@error($errorKey)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror