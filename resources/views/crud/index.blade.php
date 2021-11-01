@extends('dashboard::layout.app')

@section('pageTitle'){{ $pageTitle }}@endsection

@section('content')
    <?php
    /** @var \Davesweb\Dashboard\Services\Table $table */
    ?>
    <div>
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="w-25">
                    <label class="form-label d-flex align-items-start text-muted" for="per-page">
                        <span class="me-2 pt-1">{{ __('Show') }}</span>
                        <select class="form-control form-control-sm w-25 me-2" name="per-page" id="per-page">
                            <option value="15">15</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="pt-1">{{ __('per page') }}</span>
                    </label>
                </div>
                <div class="ms-auto d-flex align-items-start">
                    @if($table->hasSearch())
                        <div class="input-group pt-1 me-2">
                            <input type="search" class="form-control form-control-sm" placeholder="{{ __('Search') }}" aria-label="{{ __('Search') }}" aria-describedby="search-addon">
                            <button class="input-group-text btn-secondary" id="search-addon"><i class="fa fa-search"></i></button>
                        </div>
                    @endif
                    @if($table->hasTranslations() && count(config('app.available_locales', [])) > 1)
                        <div class="dropdown pt-1">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="language-select" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="{{ config('app.available_locales.' . $crudLocale . '.icon', []) }}"></span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="language-select">
                                @foreach(config('app.available_locales', []) as $abbr => $locale)
                                    <li><a class="dropdown-item" href="{{ full_url_with_query(['locale' => $abbr]) }}"><span class="{{ $locale['icon'] }}"></span> {{ $locale['name'] }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <table class="card-body table">
                <thead>
                    <tr>
                        @foreach($table->getHeaders() as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach($items->items() as $model)
                            @foreach($table->getColumns() as $column)
                                <th>{!! $column->render($model, $crudLocale) !!}</th>
                            @endforeach
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection