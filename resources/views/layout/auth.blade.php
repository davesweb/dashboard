<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>Davesweb</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dashboard/css/app.css') }}" />
    </head>
    <body class="bg-light">
        @yield('content')
    </body>
</html>
