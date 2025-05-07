<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ForumComment;
use App\Models\ForumCommentVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumCommentVoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_vote()
    {
        $user = User::factory()->create();
        $comment = ForumComment::factory()->create();

        $vote = ForumCommentVote::create([
            'forum_comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('forum_comment_votes', [
            'forum_comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $vote = ForumCommentVote::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $vote->user);
        $this->assertEquals($user->id, $vote->user->id);
    }

    /** @test */
    public function it_belongs_to_a_comment()
    {
        $comment = \App\Models\ForumComment::factory()->create();

        $vote = \App\Models\ForumCommentVote::factory()->create([
            'forum_comment_id' => $comment->id,
        ]);

        $vote->refresh();

        $this->assertInstanceOf(\App\Models\ForumComment::class, $vote->comment);
        $this->assertEquals($comment->id, $vote->comment->id);
    }
}
