<div class="mb-3">
    @if(!$translatable)
        <label class="form-label" for="{{ $name }}">{{ $label }}</label>
        <input
            class="form-control @error($name) is-invalid @enderror"
            type="{{ $type ?? 'text' }}"
            name="{{ $name }}"
            id="{{ $name }}"
            value="{{ $value($model ?? null, $formLocale) }}"
            placeholder="{{ $placeholder }}"
            @if($required) required="required" @endif
            @if($autofocus) autofocus="autofocus" @endif
        />
        @error($name)
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @else
        @if(!$inSection)
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
        @else
            <label class="form-label" for="{{ $name }}[translated][{{ $formLocale }}]">{{ $label }}</label>
        @endif
        @if(!$inSection)
            <div class="tab-content" id="nav-tabContent">
                @foreach(config('app.available_locales', []) as $abbr => $locale)
                    <div class="tab-pane {{ $formLocale === $abbr ? 'show active' : '' }}" id="nav-{{ $abbr.$name }}" role="tabpanel" aria-labelledby="nav-{{ $abbr.$name }}-tab">
                        <input
                            class="form-control @error($name . '.translated.' . $abbr) is-invalid @enderror"
                            type="{{ $type ?? 'text' }}"
                            name="{{ $name }}[translated][{{ $abbr }}]"
                            id="{{ $name }}{{ $abbr }}"
                            value="{{ $value($model ?? null, $abbr) }}"
                            placeholder="{{ $placeholder }}"
                            @if($required) required="required" @endif
                            @if($autofocus) autofocus="autofocus" @endif
                            aria-label="{{ $label }}"
                        />
                        @error($name . '.translated.' . $abbr)
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>
        @else
            <input
                class="form-control @error($name . '.translated.' . $formLocale) is-invalid @enderror"
                type="{{ $type ?? 'text' }}"
                name="{{ $name }}[translated][{{ $formLocale }}]"
                id="{{ $name }}{{ $formLocale }}"
                value="{{ $value($model ?? null, $formLocale) }}"
                placeholder="{{ $placeholder }}"
                @if($required) required="required" @endif
                @if($autofocus) autofocus="autofocus" @endif
            />
            @error($name . '.translated.' . $formLocale)
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        @endif
    @endif
</div>