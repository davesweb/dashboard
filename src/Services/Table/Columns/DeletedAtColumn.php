<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeletedAtColumn extends DateTimeColumn
{
    /**
     * @param Model|SoftDeletes $model
     */
    public function render(Model $model, string $locale): string|HtmlString
    {
        if (method_exists($model, 'getDeletedAtColumn')) {
            $this->content($model->getDeletedAtColumn());
        } else {
            $this->content('deleted_at');
        }

        return parent::render($model, $locale);
    }
}
