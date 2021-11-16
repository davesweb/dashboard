<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>Davesweb</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dashboard/css/auth.css') }}" />
    </head>
    <body style="background-image: url('{{ asset('vendor/dashboard/images/galaxy3.jpg') }}')">
        <div class="container">
            <div class="row d-flex min-vh-100">
                <div class="col-md-8 offset-md-2 col-xl-6 offset-xl-3 col-xxl-4 offset-xxl-4 justify-content-center align-self-center">
                    @yield('content')
                </div>

                <div class="col-xxl-4 offset-xxl-4 justify-content-center align-self-end text-center">
                    {!! __('Copyright 2020-:year <a href=":url" target="_blank" class="text-dark-50">davesweb</a>.', ['year' => date('Y'), 'url' => 'https://github.com/davesweb/dashboard#readme']) !!}
                </div>
            </div>
        </div>
    </body>
    <script src="{{ asset('vendor/dashboard/js/app.js') }}"></script>
</html>
