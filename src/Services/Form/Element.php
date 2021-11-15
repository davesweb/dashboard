<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Form;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

abstract class Element
{
    abstract public function render(?Model $model, string $locale, iterable $availableLocales = [], bool $inSection = false): HtmlString;
}
