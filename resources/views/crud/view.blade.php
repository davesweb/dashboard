@extends('dashboard::layout.app')

@section('pageTitle'){{ $pageTitle }}@endsection

@section('content')
    <?php
    /** @var \Davesweb\Dashboard\Services\Table $table */
    ?>
    <div>
        <div class="card p-3">
            <div class="d-flex justify-content-between align-items-start">
                <div class="ms-auto d-flex align-items-start">
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
                <tbody>
                    @foreach($table->getColumns() as $column)
                        <tr>
                            <td>{{ $column->getTitle() }}</td>
                            <td>{!! $column->render($model, $crudLocale) !!}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection