<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class CreatedAtColumn extends DateTimeColumn
{
    protected string $format = '%d %B %Y';

    public function render(Model $model, string $locale): string|HtmlString
    {
        $this->content($model->getCreatedAtColumn());

        return parent::render($model, $locale);
    }
}
