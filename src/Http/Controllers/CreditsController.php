<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;

class CreditsController extends Controller
{
    public function show(): Renderable
    {
        return view('dashboard::credits');
    }
}
