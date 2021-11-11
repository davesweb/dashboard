<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Util\HtmlElement;

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

    public function render(Model $model, string $locale): string|HtmlElement
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
