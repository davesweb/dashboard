@extends('dashboard::layout.auth')

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <div class="card-title">
                <h3>{{ __('Credits') }}</h3>
            </div>
        </div>
        <div class="card-body">
            @if(auth('dashboard')->check())
                <p>
                    <a href="{{ dashboard_route('index') }}">&#8592; {{ __('Back to dashboard') }}</a>
                </p>
            @endif
            <p>
                This dashboard is open source and available for free to anyone. We also utilize some other open source and free utilities to improve this dashboard.
            </p>
            <p>
                <strong>Backend framework:</strong><br />
                Laravel 8
            </p>
            <p>
                <strong>Frontend framework:</strong><br />
                Bootstrap 5
            </p>
            <p>
                <strong>The background image on the auth pages:</strong><br />
                Photo by <a href="https://unsplash.com/@ryan_hutton_?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" target="_blank">Ryan Hutton</a> on <a href="https://unsplash.com/?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText" target="_blank">Unsplash</a>
            </p>
            <p class="mb-0">
                <strong>Plugins &amp; packages:</strong><br />
            </p>
            <ul class="mt-0">
                <li><a href="https://editorjs.io/" target="_blank">Editor.js</a> &amp; <a href="https://github.com/editor-js/awesome-editorjs" target="_blank">plugins</a></li>
                <li><a href="https://fontawesome.com/" target="_blank">Font Awesome</a></li>
                <li><a href="https://github.com/lipis/flag-icons" target="_blank">CSS Flag icons</a></li>
            </ul>
            <p>
                <strong>Larger SVG icons &amp; images:</strong><br />
                <a href="https://undraw.co/" target="_blank">Undraw</a>
            </p>
            <p>
                If you like this dashboard or use it in a production environment, please consider subscribing to our Patreon for premium support:
                <a href="https://www.patreon.com/davesweb" target="_blank">Davesweb Patreon</a>.
            </p>
        </div>
    </div>
@endsection