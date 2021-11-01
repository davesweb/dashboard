<?php

namespace Davesweb\Dashboard\Services\Table;

use Closure;
use Davesweb\Dashboard\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;

/**
 * @todo button color, confirmation modal
 */
class Action
{
    private string $title;

    private ?string $icon;

    private ?string $href;

    private ?string $route;

    private null|string|Closure $can;

    private ?string $ability;

    private ?string $formMethod;

    public function __construct(string $title, ?string $href = null, ?string $route = null, ?string $icon = null, string|Closure|null $can = null, ?string $ability = null, ?string $formMethod = null)
    {
        $this->title      = $title;
        $this->href       = $href;
        $this->route      = $route;
        $this->icon       = $icon;
        $this->can        = $can;
        $this->ability    = $ability;
        $this->formMethod = $formMethod;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getHref(): ?string
    {
        return $this->href;
    }

    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function getCan(): Closure|string|null
    {
        return $this->can;
    }

    public function getFormMethod(): ?string
    {
        return $this->formMethod;
    }

    public function can(Model $model, User $user): bool
    {
        if ($this->can instanceof Closure) {
            return call_user_func($this->can, $model, $user);
        }

        if (!empty($this->can)) {
            // Todo: once the permission library is installed, check for this specific permission
        }

        if (!empty($this->ability)) {
            return Gate::forUser($user)->allows($this->ability, $model);
        }

        // No abilities or custom checks provided
        return true;
    }
}
