<?php
/** @var \Davesweb\Dashboard\Services\Table\Action[] $actions */
?>
@foreach($actions as $action)
    @if($action->can($model, request()->user('dashboard')))
        @if($action->getFormMethod() !== null)
            <form method="post" class="d-inline" action="{{ $action->getHref() ?? route($action->getRoute(), [$model]) }}">
                @csrf
                @method($action->getFormMethod())
                <button type="submit" title="{{ $action->getTitle() }}" class="btn btn-secondary btn-sm">{!! $action->getIcon() === null ? $action->getTitle() : $action->getIcon() !!}</button>
            </form>
        @else
            <a href="{{ $action->getHref() ?? route($action->getRoute(), [$model]) }}" title="{{ $action->getTitle() }}" class="btn btn-secondary btn-sm">
                {!! $action->getIcon() === null ? $action->getTitle() : $action->getIcon() !!}
            </a>
        @endif
    @endif
@endforeach