<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Davesweb\Dashboard\Models\User;

class ForceTwoFactorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('dashboard.users.require-2fa', false)) {
            return $next($request);
        }

        if ($request->routeIs($this->excluded())) {
            return $next($request);
        }

        /** @var User $user */
        $user = $request->user('dashboard');

        if (null === $user || null !== $user->two_factor_secret) {
            return $next($request);
        }

        return redirect()->route(config('dashboard.route-prefix') . 'settings.edit');
    }

    protected function excluded(): iterable
    {
        return [
            config('dashboard.route-prefix') . 'settings.edit',
            config('dashboard.route-prefix') . 'settings.update',
            'two-factor.enable',
            'two-factor.qr-code',
            'password.confirmation',
            'password.confirm',
        ];
    }
}
