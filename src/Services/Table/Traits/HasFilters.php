<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Services\Table\Traits;

use Closure;
use Illuminate\Support\Str;

trait HasFilters
{
    protected array $filters = [];

    protected array $titles = [];

    public function filter(string $name, Closure $filter, ?string $title = null): static
    {
        $this->filters[$name] = $filter;

        $this->titles[$name] = $title ?? (string) Str::of($name)->snake(' ')->title();

        return $this;
    }

    public function hasFilters(): bool
    {
        return count($this->filters) > 0;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getFilter(string $name): Closure
    {
        return $this->filters[$name];
    }

    public function filterExists(string $name): bool
    {
        return isset($this->filters[$name]);
    }

    public function getFilterTitles(): array
    {
        return $this->titles;
    }

    public function getFilterTitle(string $name): string
    {
        return $this->titles[$name];
    }
}
