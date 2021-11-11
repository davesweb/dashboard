<div class="mb-3">
    <label class="form-label" for="{{ $for }}">{{ $label }}</label>
    @include('dashboard::crud.form.textarea-raw', [
        'errorLey'     => $errorKey,
        'type'         => $type,
        'name'         => $name,
        'id'           => $id,
        'value'        => $value,
        'model'        => $model,
        'locale'       => $formLocale,
        'placeholder'  => $placeholder,
        'required'     => $required,
        'autofocus'    => $autofocus,
        'ariaLabel'    => $ariaLabel,
        'rows'         => $rows,
        'info'         => $info,
    ])
</div>