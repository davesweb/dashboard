<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Support\Renderable;
use Davesweb\Dashboard\Providers\ServiceProvider;

class UpdatesController extends Controller
{
    public function index(): Renderable
    {
        $releases = Http::get('https://api.github.com/repos/davesweb/dashboard/releases')->json();

        return view('dashboard::updates.index', [
            'version'       => ServiceProvider::VERSION,
            'latestVersion' => $releases[0]['name'] ?? ServiceProvider::VERSION,
            'releases'      => $releases,
        ]);
    }
}
