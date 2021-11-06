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
                <button type="submit" name="submit" class="btn btn-primary">{{ __('Edit :model', ['model' => $crud->singular()]) }}</button>
            </div>
        </div>
    </form>
@endsection