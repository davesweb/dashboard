<header id="main-header" class="mb-md-4 py-3 d-flex justify-content-between align-items-start">
    <div>
        <button class="toggle-sidebar btn btn-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="true" aria-controls="sidebar"><i class="fa fa-bars"></i></button>
    </div>
    <div class="ms-3 d-none d-md-inline-block">
        <h2 class="m-0">@yield('pageTitle')</h2>
    </div>
    @if(isset($pageActions) && count($pageActions) > 0)
        <div class="page-actions btn-group ms-3" role="group" aria-label="{{ __('Page actions') }}">
            @foreach($pageActions as $action)
                <a class="btn btn-primary" href="{{ route($action->getRoute()) }}">{{ $action->getIcon() }} {{ $action->getTitle() }}</a>
            @endforeach
        </div>
    @endif
    <div class="ms-auto">
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
                {{ config('dashboard.available-locales.' . app()->getLocale() . '.icon') }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="locale-dropdown">
                @foreach(config('dashboard.available-locales', []) as $abbr => $locale)
                    <li><a class="dropdown-item" href="{{ dashboard_route('index', ['locale' => $abbr]) }}">{{ $locale['icon'] }} {{ $locale['name'] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="dropdown d-inline-block">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-user me-2"></i> <span class="d-none d-md-inline-block">{{ auth()->user()->name }}</span>
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
<div class="d-md-none mb-4 mb-md-0">
    <h2>@yield('pageTitle')</h2>
</div>