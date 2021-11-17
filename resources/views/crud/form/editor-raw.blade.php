<div id="{{ $id }}" class="form-control" data-name="{{ $name }}"></div>

@section('scripts')
    @parent
    <script src="{{ asset('vendor/dashboard/js/editor.js') }}"></script>
    <script>
        new Editor('{{ $id }}', @json($value($model ?? null, $locale)));
    </script>
@endsection