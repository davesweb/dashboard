<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers\Auth;

use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Controllers\Controller;

class UpdatePasswordController extends Controller
{
    public function edit(): Renderable
    {
        return view('dashboard::auth.update-password');
    }
}
