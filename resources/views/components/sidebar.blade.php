<aside id="sidebar" class="collapse show" style="transition: none !important;">
    <nav>
        <h1 class="brand h3 mb-0 p-1">
            <a href="{{ dashboard_route('index') }}" class="text-decoration-none">
                <i class="fa fa-home text-secondary align-middle mb-1"></i>
                <span>{{ config('app.name') }}</span>
            </a>

            <button class="btn btn-outline-secondary btn-sm fa-pull-right border-0 d-md-none" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-expanded="true" aria-controls="sidebar"><i class="fa fa-close"></i></button>
        </h1>
        <div class="menu-divider mb-4 mt-0"></div>
        @foreach(\Davesweb\Dashboard\Layout\Sidebar\Sidebar::factory()->getMenus() as $menu)
            @if($menu instanceof \Davesweb\Dashboard\Layout\Sidebar\Divider)
                <div class="menu-divider mb-4"></div>
                @continue
            @endif

            @if(!empty($menu->getTitle()))
                <h5 class="menu-heading h6 text-uppercase">{{ $menu->getTitle() }}</h5>
            @endif
            <ul class="sidebar-menu mb-4 list-unstyled mb-4">
                @foreach($menu->getLinks() as $link)
                    @if($link->hasSubmenu())
                        <li class="has-submenu">
                            <a href="#{{ $link->getSubmenu()->getHref() }}" class="{{ $link->isActive(request()) ? ' active' : '' }} collapsed" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="{{ $link->getSubmenu()->getHref() }}"><span class="me-2">{{ $link->getIcon() }}</span> {{ $link->getTitle() }}</a>
                            <ul id="{{ $link->getSubmenu()->getHref() }}" class="collapse list-unstyled submenu">
                                @foreach($link->getSubmenu()->getLinks() as $subLink)
                                    <li><a href="{{ $subLink->getHref() }}" {{ $subLink->getTarget() !== null ? 'target="' . $subLink->getTarget() . '"' : '' }}>{{ $subLink->getTitle() }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li><a href="{{ $link->getHref() }}" class="{{ $link->isActive(request()) ? ' active' : '' }}" {{ $link->getTarget() !== null ? 'target="' . $link->getTarget() . '"' : '' }}><span class="me-2">{{ $link->getIcon() }}</span> {{ $link->getTitle() }}</a></li>
                    @endif
                @endforeach
            </ul>
        @endforeach
    </nav>
</aside>