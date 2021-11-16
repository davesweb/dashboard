<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />

        <title>@yield('pageTitle', __('Home')) - {{ config('app.name') . ' ' . __('Dashboard') }}</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/dashboard/css/app.css') }}" />
    </head>
    <body class="bg-dark-500">
        @include('dashboard::components.sidebar')
        <section id="main-content">
            @include('dashboard::components.header')
            <main class="{{ isset($pageWidth) && $pageWidth === 'full' ? '' : 'container' }}">
                @yield('content')
            </main>
        </section>
    @section('scripts')
        <script src="{{ asset('vendor/dashboard/js/app.js') }}"></script>
    @show
</html>