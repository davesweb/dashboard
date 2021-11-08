<?php

declare(strict_types=1);

namespace Davesweb\Dashboard\Tests\Factories;

use Davesweb\Dashboard\Tests\Models\CrudModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CrudModelFactory extends Factory
{
    protected $model = CrudModel::class;

    public function definition()
    {
        return [
            'title'      => $this->faker->words(5, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
