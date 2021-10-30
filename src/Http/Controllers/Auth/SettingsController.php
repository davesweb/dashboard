<?php

namespace Davesweb\Dashboard\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Http\Controllers\Controller;

class SettingsController extends Controller
{
    public function edit(Request $request): Renderable
    {
        return view('dashboard::auth.settings', ['user' => $request->user('dashboard')]);
    }
}
