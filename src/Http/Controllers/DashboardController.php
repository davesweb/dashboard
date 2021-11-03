<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class DashboardController extends Controller
{
    public function index(): Renderable
    {
        return view('dashboard::index');
    }
}
