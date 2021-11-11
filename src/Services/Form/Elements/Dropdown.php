<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class Dropdown extends Input
{
    protected string $tabbedView = 'dashboard::crud.form.tabbed-dropdown';

    protected string $view = 'dashboard::crud.form.dropdown';

    private iterable $options = [];

    public function options(iterable $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function option(string $title, mixed $value = null): static
    {
        $this->options[$value ?? $title] = $title;

        return $this;
    }

    public function getOptions(): iterable
    {
        return $this->options;
    }

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        $this->extraData['options'] = $this->getOptions();

        return parent::render($model, $locale, $availableLocales, $inSection);
    }
}
