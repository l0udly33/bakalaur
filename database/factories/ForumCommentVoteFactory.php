<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ForumComment;
use App\Models\User;
use App\Models\ForumCommentVote;

class ForumCommentVoteFactory extends Factory
{
    protected $model = ForumCommentVote::class;

    public function definition(): array
    {
        return [
            'forum_comment_id' => ForumComment::factory(),
            'user_id' => User::factory(),
        ];
    }
}
