@extends('dashboard::layout.app')

@section('pageTitle'){{ $pageTitle }}@endsection

@section('content')
    <?php
    /** @var \Davesweb\Dashboard\Services\Form $form */
    ?>
    <form method="{{ $form->getMethod() }}" action="{{ $form->getAction() }}" {!! $form->hasMedia() ? 'enctype="multipart/form-data"' : ''; !!}>
        @csrf
        @if($form->getMethod() !== 'post' && $form->getMethod() !== 'get')
            @method($form->getMethod())
        @endif

        @include('dashboard::crud.form')
        <div class="card">
            <div class="card-body">
                <button type="submit" name="submit" class="btn btn-primary">{{ __('Save :model', ['model' => $crud->singular()]) }}</button>
                <button type="submit" name="submit-another" class="btn btn-secondary">{{ __('Save :model and create another', ['model' => $crud->singular()]) }}</button>
            </div>
        </div>
    </form>
@endsection