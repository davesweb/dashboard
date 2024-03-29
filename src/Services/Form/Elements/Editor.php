<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class Editor extends Input
{
    protected string $tabbedView = 'dashboard::crud.form.tabbed-editor';

    protected string $view = 'dashboard::crud.form.editor';

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        return parent::render($model, $locale, $availableLocales, $inSection);
    }
}
