<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>Davesweb</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dashboard/css/app.css') }}" />
    </head>
    <body class="bg-dark-500">
        <div class="container">
            <div class="row">
                <main class="col-12 col-md-8 col-xl-4 offset-md-2 offset-xl-4 min-vh-100 d-flex flex-column">
                    <header class="my-4">
                        <img src="#" alt="Logo" />
                    </header>
                    <section class="auth-form">
                        @yield('content')
                    </section>
                    <footer class="mt-auto text-muted">
                        <p>
                            &copy;2020-{{ date('Y') }} - <a href="https://github.com/davesweb/dashboard/" target="_blank" class="text-dark-100 text-decoration-none">Davesweb Dashboard</a>
                        </p>
                    </footer>
                </main>
            </div>
        </div>
    </body>
    <script src="{{ asset('vendor/dashboard/js/app.js') }}"></script>
</html>
