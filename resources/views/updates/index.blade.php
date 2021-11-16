@extends('dashboard::layout.app')

@section('pageTitle')
    {{ __('Updates') }}
@endsection

@section('content')
    <div class="card card-primary">
        <div class="card-header header-sm width-auto">
            <h3 class="card-title h5">{{ __('Current version') }}</h3>
        </div>
        <div class="card-body">
            {!! __('The current version of your dashboard is <code>:version</code>. The latest released version for the dashboard is <code>:release_version</code>.', ['version' => $version, 'release_version' => $latestVersion]) !!}
            @if(version_compare($version, $latestVersion, '='))
                {{ __('You are currently using the latest release version. Congratulations!') }}
            @else
                {{ __('You are currently not using the latest release version. Please consider upgrading to stay up to date with the latest bugfixes, security patches and feature releases.') }}
            @endif
        </div>
    </div>

    <h4 class="mt-3">{{ __('Previous versions and release notes') }}</h4>

    @foreach($releases as $release)
        <div class="card">
            <div class="card-header header-sm width-auto">
                <h3 class="card-title h5">{{ $release['name'] }}</h3>
            </div>
            <div class="card-body">
                {!! nl2br($release['body']) !!}
            </div>
        </div>
    @endforeach
@endsection