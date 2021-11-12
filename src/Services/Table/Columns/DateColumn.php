<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Columns;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Model;

class DateColumn extends Column
{
    protected string $format = 'Y-m-d';

    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function render(Model $model, string $locale): string|HtmlString
    {
        if (null === $this->content) {
            $this->content = (string) Str::of($this->title)->snake();
        }

        $getter = 'get' . Str::of($this->content)->camel()->ucfirst();

        if (method_exists($model, $getter)) {
            $date = call_user_func([$model, $getter]);
        } else {
            $date = $model->{$this->content};
        }

        if ($date instanceof Carbon) {
            $date->locale($locale);
        }

        return $date->format($this->getFormat());
    }
}
