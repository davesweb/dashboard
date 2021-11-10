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
                                    <a href="#{{ $link->getSubmenu()->getHref() }}" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $link->getSubmenu()->getHref() }}"><span class="me-2">{{ $link->getIcon() }}</span> {{ $link->getTitle() }}</a>
                                    <ul id="{{ $link->getSubmenu()->getHref() }}" class="collapse">
                                        @foreach($link->getSubmenu()->getLinks() as $subLink)
                                            <li><a href="{{ $subLink->getHref() }}">{{ $subLink->getTitle() }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                <li><a href="{{ $link->getHref() }}"><span class="me-2">{{ $link->getIcon() }}</span> {{ $link->getTitle() }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                @endforeach
            </div>
        </nav>
        <main id="page" class="d-flex flex-column">
            <header id="header" class="p-3 bg-dark-300 d-flex justify-content-between align-items-start">
                <div class="me-auto">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="quick-start-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('Quick links') }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="quick-start-dropdown">
                            <li><a class="dropdown-item" href="#"><i class="fa fa-user-plus me-2"></i> {{ __('New user') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fa fa-file me-2"></i> {{ __('New page') }}</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fa fa-cog me-2"></i> {{ __('Site settings') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div>
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="locale-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-bell"></i>
                            <span class="position-absolute top-0 start-75 translate-middle badge rounded-pill bg-danger">
                                99+
                                <span class="visually-hidden">{{ __('unread messages') }}</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="locale-dropdown">
                            <li><a class="dropdown-item" href="#">Message</a></li>
                        </ul>
                    </div>
                    <div class="dropdown d-inline-block me-2">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="locale-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="flag-icon flag-icon-en"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="locale-dropdown">
                            <li><a class="dropdown-item" href="#"><span class="flag-icon flag-icon-nl"></span> Nederlands</a></li>
                            <li><a class="dropdown-item" href="#"><span class="flag-icon flag-icon-de"></span> Deutsch</a></li>
                            <li><a class="dropdown-item" href="#"><span class="flag-icon flag-icon-es"></span> Español</a></li>
                            <li><a class="dropdown-item" href="#"><span class="flag-icon flag-icon-fi"></span> Suomalainen</a></li>
                        </ul>
                    </div>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user me-2"></i> {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="user-dropdown">
                            <li><a class="dropdown-item" href="{{ dashboard_route('profile.edit') }}">{{ __('Profile') }}</a></li>
                            <li><a class="dropdown-item" href="{{ dashboard_route('password.edit') }}">{{ __('Change password') }}</a></li>
                            <li><a class="dropdown-item" href="{{ dashboard_route('settings.edit') }}">{{ __('Settings') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">{{ __('Logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <section class="page p-3">
                <div class="container">
                    <div class="header d-flex justify-content-between align-items-start">
                        <div class="mb-2 w-50">
                            <h1 class="page-title w-100">@yield('pageTitle')</h1>
                            @if(isset($pageActions) && count($pageActions) > 0)
                                <div class="page-actions btn-group btn-group-sm" role="group" aria-label="{{ __('Page actions') }}">
                                    @foreach($pageActions as $action)
                                        <a class="btn btn-primary" href="{{ route($action->getRoute()) }}">{{ $action->getIcon() }} {{ $action->getTitle() }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="breadcrumbs ms-auto">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Library</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </section>

            <footer class="bg-dark-600 mt-auto p-3">
                <p class="text-muted mb-0">
                    &copy; 2020-{{ date('Y') }} - Davesweb Dashboard
                </p>
            </footer>
        </main>

        <div class="toast-container position-absolute p-4" style="bottom: 0; right: 0; width: 400px; min-height: 200px;">
            @foreach(['success', 'error', 'warning', 'info'] as $type)
                @if(session()->has($type))
                    <div class="toast show toast-success" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto">{{ __(ucfirst($type)) }}</strong>
                            <small class="text-muted">{{ __('just now') }}</small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="{{ __('Close') }}"></button>
                        </div>
                        <div class="toast-body">
                            {{ session()->get($type) }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </body>
    <script src="{{ asset('vendor/dashboard/js/app.js') }}"></script>
</html>