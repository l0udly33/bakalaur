<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumPostVote;

class ForumPostVoteFactory extends Factory
{
    protected $model = ForumPostVote::class; // ← BŪTINA!


    public function definition(): array
    {
        return [
            'forum_post_id' => ForumPost::factory(),
            'user_id' => User::factory(),
        ];
    }
}
