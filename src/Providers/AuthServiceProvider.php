<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Providers;

use Illuminate\Support\Facades\Gate;
use Davesweb\Dashboard\Policies\CrudPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [];

    public function boot(): void
    {
        $this->registerPolicies();
    }

    public function register()
    {
        // todo test with policies registered in the app and not in the package
        Gate::before(function ($user, $ability, $arguments) {
            // If there is a policy registered for this object/class, let that policy handle it.
            if (isset($arguments[0]) && null !== Gate::getPolicyFor($arguments[0])) {
                return null;
            }

            // If no policy is registered, pass it to our own CrudPolicy
            $policy = resolve(CrudPolicy::class);

            return call_user_func([$policy, $ability], $user, array_shift($arguments), $arguments);
        });
    }
}
