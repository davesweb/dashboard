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
                    <div class="form-label d-flex align-items-start text-muted" >
                        <span class="pt-1">{{ __('Show') }}</span>
                        <div class="dropdown pt-0 mx-2">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="per-page-select" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $perPage }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="per-page-select">
                                <li><a class="dropdown-item" href="{{ full_url_with_query(['perPage' => 15]) }}">15</a></li>
                                <li><a class="dropdown-item" href="{{ full_url_with_query(['perPage' => 25]) }}">25</a></li>
                                <li><a class="dropdown-item" href="{{ full_url_with_query(['perPage' => 50]) }}">50</a></li>
                                <li><a class="dropdown-item" href="{{ full_url_with_query(['perPage' => 100]) }}">100</a></li>
                            </ul>
                        </div>
                        <span class="pt-1">{{ __('per page') }}</span>
                    </div>
                </div>
                <div class="ms-auto d-flex align-items-start">
                    @if($table->hasSearch())
                        <form method="get" action="{{ full_url_with_query([]) }}">
                            <div class="input-group pt-1 me-2">
                                <input type="search" name="q" class="form-control form-control-sm" placeholder="{{ __('Search') }}" aria-label="{{ __('Search') }}" aria-describedby="search-addon" value="{{ $searchQuery }}" tabindex="1" />
                                @foreach(request()->all() as $key => $value)
                                    @if($key !== 'q')
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                                    @endif
                                @endforeach
                                <button class="input-group-text btn-secondary" id="search-addon"><i class="fa fa-search"></i></button>
                            </div>
                        </form>
                    @endif
                    @if($table->hasTranslations() && count(config('app.available_locales', [])) > 1)
                        <div class="dropdown pt-1 ms-2">
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
                    @if($table->hasExports())
                        <div class="dropdown pt-1 ms-2">
                            <button class="btn btn-secondary dropdown-toggle btn-sm" type="button" id="export-select" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('Export') }}
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="language-select">
                                @foreach($table->getExports() as $export)
                                    <li><a class="dropdown-item" href="#">{{ export_icon($export) }} {{ strtoupper($export) }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
            <table class="card-body table">
                <thead>
                    <tr>
                        @foreach($table->getColumns() as $column)
                            <th{!! $column->getWidth() !== null ? ' style="width: ' . $column->getWidth() . '"' : '' !!}>
                                {{ $column->getTitle() }}
                                @if($column->isOrderable())
                                    <a href="{{ full_url_with_query(['sort' => $column->getOrderColumn(), 'dir' => 'DESC']) }}" class="sort-link{{ $sort === $column->getOrderColumn() && $dir === 'DESC' ? ' active' : '' }}"><i class="fa-pull-right fa fa-sort-alpha-up text-muted fs-7 mt-1"></i></a>
                                    <a href="{{ full_url_with_query(['sort' => $column->getOrderColumn(), 'dir' => 'ASC']) }}" class="sort-link{{ $sort === $column->getOrderColumn() && $dir === 'ASC' ? ' active' : '' }}"><i class="fa-pull-right fa fa-sort-alpha-down text-muted fs-7 mt-1"></i></a>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($items->items() as $model)
                        <tr>
                        @foreach($table->getColumns() as $column)
                            <td>{!! $column->render($model, $crudLocale) !!}</td>
                        @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-start">
            {!! $items->links('dashboard::crud.partials.pagination') !!}
        </div>
    </div>
@endsection