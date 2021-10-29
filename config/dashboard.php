<?php

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
        'web'
    ],
];
