@extends('dashboard::layout.auth')

@section('content')
    <div class="card bg-dark-400 text-dark-50">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ __('Two factor login') }}</h2>
            <form method="post" action="/{{ config('dashboard.route') }}/two-factor-challenge">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">{{ __('Code') }}</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" />
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#recovery-code-area" aria-expanded="false" aria-controls="recovery-code-area">
                        {{ __('Use a recovery code instead') }}
                    </button>
                </div>
                <div class="collapse" id="recovery-code-area">
                    <div class="mb-3">
                        <label for="recovery_code" class="form-label">{{ __('Recovery code') }}</label>
                        <input type="text" name="recovery_code" id="recovery_code" class="form-control @error('recovery_code') is-invalid @enderror" />
                        @error('recovery_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary text-center w-100">{{ __('Confirm login') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
