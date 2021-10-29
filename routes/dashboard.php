<?php

use Illuminate\Routing\Router;
use Davesweb\Dashboard\Http\Middleware\Authenticate;
use Davesweb\Dashboard\Http\Controllers\DashboardController;
use Davesweb\Dashboard\Http\Controllers\Auth\LoginController;

/* @var Router $router */
$router->get('login', [LoginController::class, 'showView'])->name('login');

$router->group(['middleware' => Authenticate::class . ':dashboard'], function (Router $router) {
    $router->get('/', [DashboardController::class, 'index'])->name('index');
});
