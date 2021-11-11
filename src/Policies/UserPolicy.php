<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Policies;

use Davesweb\Dashboard\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        // Todo
        return true;
    }

    public function viewTrashed(User $user): bool
    {
        // Todo
        return true;
    }

    public function view(User $user, User $model): bool
    {
        // Todo
        return true;
    }

    public function create(User $user): bool
    {
        // Todo
        return true;
    }

    public function update(User $user, User $model): bool
    {
        // Todo
        return true;
    }

    public function destroy(User $user, User $model): bool
    {
        // Todo
        return false;
    }

    public function destroyTrashed(User $user, User $model): bool
    {
        // Todo
        return false;
    }
}
