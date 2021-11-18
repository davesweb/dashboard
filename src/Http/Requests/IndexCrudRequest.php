<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Requests;

use Illuminate\Database\Eloquent\Model;

class IndexCrudRequest extends CrudRequest
{
    public function hasSearch(): bool
    {
        return $this->has('q') && null !== $this->get('q');
    }

    public function getSearchQuery(): ?string
    {
        return $this->get('q');
    }

    public function getPerPage(?Model $model = null): int
    {
        return (int) $this->get('perPage', optional($model)->getPerPage() ?? 15);
    }

    public function getSortColumn(?string $default = null): ?string
    {
        return $this->get('sort', $default);
    }

    public function getSortDirection(): string
    {
        return in_array($this->get('dir', 'ASC'), ['ASC', 'DESC'], false) ? $this->get('dir', 'ASC') : 'ASC';
    }

    public function getFilter(): ?string
    {
        return $this->get('filter');
    }
}
