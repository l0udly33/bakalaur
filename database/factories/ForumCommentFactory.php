<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ForumPost;

class ForumCommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'forum_post_id' => ForumPost::factory(),
            'user_id' => User::factory(),
            'comment' => $this->faker->sentence,
            'pinned' => $this->faker->boolean,
        ];
    }
}
