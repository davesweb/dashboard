@extends('dashboard::layout.auth')

@section('content')
    <div class="card card-primary my-auto">
        <div class="card-header">
            <div class="card-title"><h3>{{ __('Login') }}</h3></div>
        </div>
        <div class="card-body">
            <form method="post" action="/{{ config('dashboard.route') }}/user/confirm-password">
                @csrf
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autofocus />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary text-center w-100">{{ __('Confirm password') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
