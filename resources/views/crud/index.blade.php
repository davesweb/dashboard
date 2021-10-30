@extends('dashboard::layout.app')

@section('pageTitle'){{ $pageTitle }}@endsection

@section('content')
    <div>
        <div class="card p-3">
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
                                <th>{!! $column->render($model, 'nl') !!}</th>
                            @endforeach
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection