<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Database\Factories;

use Laravel\Fortify\RecoveryCode;
use Illuminate\Support\Collection;
use Davesweb\Dashboard\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name'     => $this->faker->name(),
            'email'    => $this->faker->email(),
            'password' => encrypt('password'),
        ];
    }

    public function twoFactorAuthEnabled()
    {
        /** @var TwoFactorAuthenticationProvider $provider */
        $provider = resolve(TwoFactorAuthenticationProvider::class);

        return $this->state(function (array $attributes) use ($provider) {
            return [
                'two_factor_secret'         => encrypt($provider->generateSecretKey()),
                'two_factor_recovery_codes' => encrypt(json_encode(Collection::times(8, function () {
                    return RecoveryCode::generate();
                })->all())),
            ];
        });
    }
}
