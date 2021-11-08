<?php

declare(strict_types=1);

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Http\Middleware\Authenticate;
use Davesweb\Dashboard\Http\Controllers\DashboardController;
use Davesweb\Dashboard\Http\Controllers\Auth\LoginController;
use Davesweb\Dashboard\Http\Controllers\Auth\ProfileController;
use Davesweb\Dashboard\Http\Controllers\Auth\SettingsController;
use Davesweb\Dashboard\Http\Controllers\Auth\UpdatePasswordController;

/* @var Router $router */
$router->get('login', [LoginController::class, 'showView'])->name('login');

$router->group(['middleware' => [Authenticate::class . ':dashboard']], function (Router $router) {
    $router->get('/', [DashboardController::class, 'index'])->name('index');

    $router->get('my-profile', [ProfileController::class, 'index'])->name('profile.edit');
    $router->get('change-password', [UpdatePasswordController::class, 'edit'])->name('password.edit');
    $router->get('my-settings', [SettingsController::class, 'edit'])->name('settings.edit');
    $router->put('my-settings', [SettingsController::class, 'update'])->name('settings.update');
});
