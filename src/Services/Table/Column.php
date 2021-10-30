<?php

namespace Davesweb\Dashboard\Services\Table;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Util\HtmlElement;
use Davesweb\LaravelTranslatable\Traits\HasTranslations;

class Column
{
    private string $title;
    private string|Closure|null $content = null;
    private bool $orderable              = false;
    private bool $searchable             = false;
    private bool $translated             = false;
    private ?string $view                = null;

    public function title(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function content(string|Closure $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function orderable(bool $orderable = true): static
    {
        $this->orderable = $orderable;

        return $this;
    }

    public function searchable(bool $searchable = true): static
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function translated(bool $translated = true): static
    {
        $this->translated = $translated;

        return $this;
    }

    public function render(Model|HasTranslations $model, string $locale): string|HtmlElement
    {
        $model = $this->translated ? $model->getTranslation($locale) : $model;

        if (null !== $this->view) {
            return view($this->view, ['model' => $model])->render();
        }

        if ($this->content instanceof Closure) {
            return call_user_func_array($this->content, [$model]);
        }

        if (null === $this->content) {
            $this->content = (string) Str::of($this->title)->snake();
        }

        $getter = 'get' . Str::of($this->content)->camel()->ucfirst();

        if (method_exists($model, $getter)) {
            return call_user_func([$model, $getter]);
        }

        return $model->{$this->content};
    }

    public function isOrderable(): bool
    {
        return $this->orderable;
    }

    public function isSearchable(): bool
    {
        return $this->searchable;
    }

    public function isTranslated(): bool
    {
        return $this->translated;
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
