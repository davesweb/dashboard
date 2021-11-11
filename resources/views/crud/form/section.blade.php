<div class="card mb-3">
    @if($title || $translatable)
        <div class="card-header d-flex justify-content-between align-items-start {{ $translatable ? 'pb-0' : '' }}">
            <div>
                @if($title)
                    <h3 class="card-title h5 mb-0 {{ $translatable ? 'mt-1' : '' }}">{{ $title }}</h3>
                @endif
            </div>
            <div>
                @if($translatable)
                    <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                        @foreach(config('app.available_locales', []) as $abbr => $locale)
                            <button class="nav-link {{ $formLocale === $abbr ? 'active' : '' }}" id="nav-{{ $abbr.$name }}-tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $abbr.$name }}" type="button" role="tab" aria-controls="nav-{{ $abbr.$name }}" aria-selected="{{ $formLocale === $abbr ? 'true' : 'false' }}"><span class="{{ $locale['icon'] }}"></span></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="card-body">
        @if($translatable)
            <div class="tab-content" id="nav-tabContent">
                @foreach(config('app.available_locales', []) as $abbr => $locale)
                    <div class="tab-pane {{ $formLocale === $abbr ? 'show active' : '' }}" id="nav-{{ $abbr.$name }}" role="tabpanel" aria-labelledby="nav-{{ $abbr.$name }}-tab">
                        @foreach($fields as $field)
                            {{ $field->render($model ?? null, $abbr, [], true) }}
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            @foreach($fields as $field)
                {{ $field->render($model ?? null, $formLocale, [], true) }}
            @endforeach
        @endif
    </div>
</div>