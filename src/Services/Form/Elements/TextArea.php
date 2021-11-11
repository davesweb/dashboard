<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class TextArea extends Input
{
    protected string $tabbedView = 'dashboard::crud.form.tabbed-textarea';

    protected string $view = 'dashboard::crud.form.textarea';

    private int $rows = 3;

    public function rows(int $rows): static
    {
        $this->rows = $rows;

        return $this;
    }

    public function getRows(): int
    {
        return $this->rows;
    }

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        $this->extraData['rows'] = $this->getRows();

        return parent::render($model, $locale, $availableLocales, $inSection);
    }
}
