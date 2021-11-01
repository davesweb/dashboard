@extends('dashboard::layout.app')

@section('pageTitle'){{ __('Edit my password') }}@endsection

@section('content')
    <div class="form-page">
        <form action="{{ route('user-password.update') }}" method="post">
            @csrf
            @method('put')
            <div class="card p-3">
                @if (session('status'))
                    <div class="mb-4 alert-success alert">
                        {{ __('Your password has been updated.') }}
                    </div>
                @endif
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current password') }}</label>
                    <input type="password" name="current_password" id="current_password" class="form-control @error('current_password') is-invalid @enderror" autofocus />
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New password') }}</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" />
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-4">{{ __('Update password') }}</button>
                </div>
            </div>
        </form>
    </div>
@endsection