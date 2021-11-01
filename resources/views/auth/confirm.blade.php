@extends('dashboard::layout.auth')

@section('content')
    <div class="card bg-dark-400 text-dark-50">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ __('Confirm password') }}</h2>
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
