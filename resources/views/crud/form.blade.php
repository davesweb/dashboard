@if(!$form->hasSections())
    <div class="card mb-2">
        <div class="card-body">
            @foreach($form->fields() as $field)
                {{ $field->render($model ?? null, $formLocale, []) }}
            @endforeach
        </div>
    </div>
@else
    @foreach($form->sections() as $section)
        Rendering section
    @endforeach
@endif