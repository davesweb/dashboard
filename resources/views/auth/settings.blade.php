@extends('dashboard::layout.app')

@section('pageTitle'){{ __('My Settings') }}@endsection

@section('content')
    <div class="form-page">
        <div class="card p-3">
            <div class="row">
                <div class="col-12 col-md-6">
                    <?php
                    /* @var \Davesweb\Dashboard\Models\User $user */
                    ?>
                    @if(!empty($user->two_factor_secret))
                        <div class="alert alert-success">
                            <p class="mb-0">
                                {{ __('2 factor authentication is enabled for your account!') }}
                            </p>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-12 col-md-4">
                                {!! $user->twoFactorQrCodeSvg() !!}
                            </div>
                            <div class="col-12 col-md-8">
                                <p>
                                    {{ __('Scan the QR code with your authenticator app to add it to your list of 2FA enabled sites and use it during login.') }}
                                </p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <form method="post" action="{{ route('two-factor.disable') }}">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger me-4">{{ __('Disable 2 factor authentication') }}</button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <p class="mb-0">
                                {{ __('2 factor authentication is not enabled for your account.') }}
                            </p>
                        </div>
                        <form method="post" action="{{ route('two-factor.enable') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary me-4">{{ __('Enable 2 factor authentication') }}</button>
                        </form>
                    @endif
                </div>

                <div class="col-12 col-md-6">
                    @if(!empty($user->two_factor_secret))
                        <p>
                            <a class="btn btn-primary" data-bs-toggle="collapse" href="#recovery-codes" role="button" aria-expanded="false" aria-controls="recovery-codes">
                                {{ __('Show recovery codes') }}
                            </a>
                        </p>
                        <div class="collapse bg-dark-100 p-3 text-dark-600" id="recovery-codes">
                            <code class="text-dark-600">
                                @foreach($user->recoveryCodes() as $code)
                                    {{ $code }}<br />
                                @endforeach
                            </code>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection