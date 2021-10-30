@extends('dashboard::layout.app')

@section('pageTitle'){{ __('My Profile') }}@endsection

@section('content')
    <div class="form-page">
        <form action="{{ route('user-profile-information.update') }}" method="post">
            @csrf
            @method('put')
            <div class="card p-3">
                @if (session('status'))
                    <div class="mb-4 alert-success alert">
                        {{ __('Your profile information has been updated.') }}
                    </div>
                @endif
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email address') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary me-4">{{ __('Update profile') }}</button>
                    <a href="#">{{ __('To change your password click here') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection