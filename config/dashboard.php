<?php

declare(strict_types=1);

use Davesweb\Dashboard\Http\Middleware\ForceTwoFactorAuth;
use Davesweb\Dashboard\ModelTranslators\DaveswebTranslator;

return [
    /*
     * The route is the actual URL where your dashboard is available. E.g. https://yoursite.com/dashboard
     */
    'route'        => 'dashboard',

    /*
     * This is the prefix for the name of the routes. This is configurable in case you already have routes with the same prefix.
     */
    'route-prefix' => 'dashboard.',

    /*
     * Middleware classes that are loaded for each dashboard route. Authentication and authorization is always added to routes that need id.
     */
    'middleware'   => [
        'web',
        ForceTwoFactorAuth::class,
    ],

    'users' => [
        /*
         * The table that is used by the User model to store the dashboard users in.
         */
        'table' => 'dashboard_users',

        /*
         * If this setting is set to true, users can't do anything but setup 2FA until this is done when they're logged in.
         */
        'require-2fa' => env('FORCE_2FA', false),
    ],

    'crud' => [
        /*
         * In which locations the dashboard should look for Crud classes.
         */
        'locations' => [
            app_path('Crud') => 'App\\Crud',
        ],
    ],

    /*
     * The translator to use for translating model attributes.
     */
    'translator' => DaveswebTranslator::class,
];
