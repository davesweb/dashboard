<?php

namespace Davesweb\Dashboard\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;

interface TranslatesModelAttributes
{
    public function translate(Model $model, string $locale, string|Closure $attribute): mixed;
}
