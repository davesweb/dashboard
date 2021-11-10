<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Policies;

use Davesweb\Dashboard\Models\User;

class CrudPolicy
{
    public function viewAny(User $user, string $model, array $arguments = []): bool
    {
        // todo
        return true;
    }

    public function viewTrashed(): bool
    {
        // todo
        return true;
    }

    public function view(): bool
    {
        // todo
        return true;
    }

    public function create(): bool
    {
        // todo
        return true;
    }

    public function update(): bool
    {
        // todo
        return true;
    }

    public function destroy(): bool
    {
        // todo
        return false;
    }

    public function destroyHard(): bool
    {
        // todo
        return false;
    }
}
