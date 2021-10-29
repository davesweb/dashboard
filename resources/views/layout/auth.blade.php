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
        <nav id="sidebar" class="bg-dark-400">
            <div class="p-3 d-flex justify-content-between align-items-start">
                <img src="#" alt="Logo" />
                <button class="ms-auto d-md-none" data-bs-toggle="collapse" data-bs-target="#main-sidebar-collapse" aria-expanded="false" aria-controls="main-sidebar-collapse"><i class="fa fa-bars"></i></button>
            </div>
            <div class="collapse p-3" id="main-sidebar-collapse">
                @foreach(\Davesweb\Dashboard\Layout\Sidebar\Sidebar::factory()->getMenus() as $menu)
                    @if(!empty($menu->getTitle()))
                        <h5 class="menu-title">{{ $menu->getTitle() }}</h5>
                    @endif
                    <ul class="sidebar-menu mb-4">
                        @foreach($menu->getLinks() as $link)
                            @if($link->hasSubmenu())
                                <li class="has-submenu">
                                    <a href="#{{ $link->getSubmenu()->getHref() }}" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $link->getSubmenu()->getHref() }}"><i class="{{ $link->getIcon() }} me-2"></i> {{ $link->getTitle() }}</a>
                                    <ul id="{{ $link->getSubmenu()->getHref() }}" class="collapse">
                                        @foreach($link->getSubmenu()->getLinks() as $subLink)
                                            <li><a href="{{ $subLink->getHref() }}">{{ $subLink->getTitle() }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{ $link->getHref() }}"><i class="{{ $link->getIcon() }} me-2"></i> {{ $link->getTitle() }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </nav>
        <main id="page">
            <header></header>
            <section class="p-3">
                @yield('content')
            </section>
            <footer></footer>
        </main>
    </body>
    <script src="{{ asset('vendor/dashboard/js/app.js') }}"></script>
</html>
