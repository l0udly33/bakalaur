<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'trainer_id' => User::factory(),
            'status' => $this->faker->randomElement(['pending', 'completed', 'canceled']),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'hours' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence,
        ];
    }
}
