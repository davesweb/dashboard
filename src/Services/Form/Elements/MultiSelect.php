<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form\Elements;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class MultiSelect extends Input
{
    protected bool $multiple = false;

    public function multiple(bool $multiple = true): static
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString
    {
        $this->extraData['multiple'] = $this->isMultiple();

        return parent::render($model, $locale, $availableLocales, $inSection);
    }
}
