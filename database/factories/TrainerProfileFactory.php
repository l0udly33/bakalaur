<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class TrainerProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'profile_picture' => null,
            'description' => $this->faker->sentence,
            'languages' => json_encode(['Lietuvių', 'Anglų']),
            'rank' => $this->faker->randomElement(['Radiant', 'Iron']),
            'pricing' => json_encode(['hour1' => 10, 'hour2' => 20]),
            'availability' => json_encode(['hours' => 'Pirmadienis 10-14']),
            'free_trial' => $this->faker->boolean,
            'achievements' => json_encode([['text' => 'Lietuvos čempionas']]),
        ];
    }
}
