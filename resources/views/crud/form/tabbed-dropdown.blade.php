<div class="mb-3">
    <nav>
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <label class="form-label">{{ $label }}</label>
            </div>
            <div class="ms-auto">
                <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                    @foreach(config('app.available_locales', []) as $abbr => $locale)
                        <button class="nav-link {{ $formLocale === $abbr ? 'active' : '' }}" id="nav-{{ $abbr.$name }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $abbr.$name }}" type="button" role="tab" aria-controls="nav-{{ $abbr.$name }}" aria-selected="{{ $formLocale === $abbr ? 'true' : 'false' }}"><span class="{{ $locale['icon'] }}"></span></button>
                    @endforeach
                </div>
            </div>
        </div>
    </nav>

    <div class="tab-content" id="nav-tabContent">
        @foreach(config('app.available_locales', []) as $abbr => $locale)
            <div class="tab-pane {{ $formLocale === $abbr ? 'show active' : '' }}" id="nav-{{ $abbr.$name }}" role="tabpanel" aria-labelledby="nav-{{ $abbr.$name }}-tab">
                @include('dashboard::crud.form.dropdown-raw', [
                    'errorLey'     => $name . '.translated.' . $abbr,
                    'type'         => $type,
                    'name'         => $name . '[translated][' . $abbr . ']',
                    'id'           => $name . $abbr,
                    'value'        => $value,
                    'model'        => $model,
                    'locale'       => $abbr,
                    'placeholder'  => $placeholder,
                    'required'     => $required,
                    'autofocus'    => $autofocus,
                    'ariaLabel'    => $label,
                    'info'         => $info,
                    'options'      => $options,
                ])
            </div>
        @endforeach
    </div>
</div>