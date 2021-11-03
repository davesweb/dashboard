<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Contracts;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

interface TranslatesModelAttributes
{
    public function translate(Model $model, string $locale, string|Closure $attribute): mixed;

    public function search(Builder $query, string $field, string $locale, string $searchQuery): Builder;
}
