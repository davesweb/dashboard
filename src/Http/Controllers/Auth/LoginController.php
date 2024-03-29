<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showView(): Renderable
    {
        return view('dashboard::auth.login');
    }

    public function confirm(): Renderable
    {
        return view('dashboard::auth.confirm');
    }

    public function twoFactor(): Renderable
    {
        return view('dashboard::auth.2fa');
    }
}
