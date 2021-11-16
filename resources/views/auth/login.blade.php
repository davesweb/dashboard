@extends('dashboard::layout.auth')

@section('content')
    <div class="card card-primary my-auto">
        <div class="card-header">
            <div class="card-title"><h3>{{ __('Login') }}</h3></div>
        </div>
        <div class="card-body">
            <form method="post" action="{{ dashboard_route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text" id="email-addon"><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="{{ __('Email address') }}" aria-label="{{ __('Email') }}" aria-describedby="email-addon" autofocus />
                    @error('email')
                    <div id="message-feedback" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="password-addon"><i class="fa fa-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Password') }}" aria-label="{{ __('Password') }}" aria-describedby="password-addon" />
                    @error('password')
                    <div id="message-feedback" class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" value="1" name="remember" id="remember" />
                    <label class="form-check-label" for="remember">
                        {{ __('Remember me') }}
                    </label>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary text-center w-100">{{ __('Login') }}</button>
                </div>
                <div class="mb-3 text-center">
                    <a href="#" class="text-dark-100">{{ __('Forgot password?') }}</a>
                </div>
            </form>
        </div>
    </div>
@endsection
