<?php

namespace Davesweb\Dashboard\Services\Table;

use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Util\HtmlElement;
use Davesweb\LaravelTranslatable\Traits\HasTranslations;

class ActionsColumn extends Column
{
    /**
     * @var Action[]
     */
    private array $actions;

    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }

    public function action(Action $action): self
    {
        $this->actions[] = $action;

        return $this;
    }

    public function render(Model|HasTranslations $model, string $locale): string|HtmlElement
    {
        return view('dashboard::crud.partials.actions', [
            'actions' => $this->actions,
            'model'   => $model,
            'locale'  => $locale,
        ])->render();
    }

    public function isOrderable(): bool
    {
        return false;
    }

    public function isSearchable(): bool
    {
        return false;
    }

    public function isTranslated(): bool
    {
        return false;
    }

    public function getTitle(): string
    {
        return '';
    }
}
