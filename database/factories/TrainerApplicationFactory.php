<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TrainerApplication;
use App\Models\User;

class TrainerApplicationFactory extends Factory
{
    protected $model = TrainerApplication::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => $this->faker->name,
            'rank' => $this->faker->randomElement(['Rafant', 'Lion', 'Kobra']),
            'age' => $this->faker->numberBetween(18, 50),
            'experience' => $this->faker->sentence,
            'motivation' => $this->faker->paragraph,
            'admin_notes' => null,
        ];
    }
}
