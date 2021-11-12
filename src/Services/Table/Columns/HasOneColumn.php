<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class HasOneColumn extends Column
{
    private string $relation;

    public function relation(string $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function render(Model $model, string $locale): string|HtmlString
    {
        $relation = null;

        $getter = 'get' . Str::of($this->relation)->camel()->ucfirst();
        if (method_exists($model, $getter)) {
            $relation = call_user_func([$model, $getter]);

            return parent::render($relation, $locale);
        }

        $relation = $model->{$this->relation};

        return parent::render($relation, $locale);
    }
}
