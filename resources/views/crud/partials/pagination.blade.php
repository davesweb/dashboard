@if ($paginator->hasPages())
    <nav aria-label="{{ __('Page navigation') }}" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item @if ($paginator->onFirstPage()) disabled @endif">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1" aria-disabled="true">{{ __('Previous') }}</a>
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif

            @endforeach
            <li class="page-item @if (!$paginator->hasMorePages()) disabled @endif">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">{{ __('Next') }}</a>
            </li>
        </ul>
    </nav>
@endif
