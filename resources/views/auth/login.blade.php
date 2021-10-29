@extends('dashboard::layout.auth')

@section('content')
    <form method="post" action="{{ dashboard_route('login') }}">
        <div class="input-group mb-3">
            <span class="input-group-text" id="email-addon">@</span>
            <input type="text" class="form-control" placeholder="{{ __('Email address') }}" aria-label="{{ __('Email') }}" aria-describedby="email-addon" />
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="password-addon">@</span>
            <input type="password" class="form-control" placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}" aria-describedby="password-addon" />
        </div>
    </form>
@endsection
