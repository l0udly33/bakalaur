<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ForumPostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'pinned' => $this->faker->boolean,
            'upvotes' => $this->faker->numberBetween(0, 100),
            'image_path' => null,
        ];
    }
}
