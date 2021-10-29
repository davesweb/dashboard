<?php

namespace Davesweb\Dashboard\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function showView(): Renderable
    {
        return view('dashboard::auth.login');
    }
}
