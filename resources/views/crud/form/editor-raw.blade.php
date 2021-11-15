<div id="{{ $id }}" class="form-control"></div>

@section('scripts')
    @parent
    <script src="{{ asset('vendor/dashboard/js/editor.js') }}"></script>
    <script>
        new Editor('{{ $id }}', '{!! json_encode($value($model ?? null, $locale)) !!}');
    </script>
@endsection