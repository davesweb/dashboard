<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Factories;

use Davesweb\Dashboard\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
}
