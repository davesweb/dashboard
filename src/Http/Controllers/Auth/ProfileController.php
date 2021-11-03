<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index(Request $request): Renderable
    {
        return view('dashboard::auth.profile', ['user' => $request->user('dashboard')]);
    }
}
