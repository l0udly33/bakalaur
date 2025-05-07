<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumCommentVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumCommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_comment()
    {
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        $comment = ForumComment::create([
            'user_id' => $user->id,
            'forum_post_id' => $post->id,
            'comment' => 'Labas testas!',
        ]);

        $this->assertDatabaseHas('forum_comments', [
            'user_id' => $user->id,
            'forum_post_id' => $post->id,
            'comment' => 'Labas testas!',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $comment = ForumComment::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $comment->user);
    }

    /** @test */
    public function it_belongs_to_a_post()
    {
        $post = ForumPost::factory()->create();

        $comment = ForumComment::factory()->create([
            'forum_post_id' => $post->id,
        ]);

        $this->assertInstanceOf(ForumPost::class, $comment->post);
    }

    /** @test */
    public function it_can_have_votes()
    {
        $comment = ForumComment::factory()->create();
        $vote = ForumCommentVote::factory()->create([
            'forum_comment_id' => $comment->id,
        ]);

        $this->assertTrue($comment->votes->contains($vote));
    }

    /** @test */
    public function it_knows_if_user_has_voted()
    {
        $user = User::factory()->create();
        $comment = ForumComment::factory()->create();

        ForumCommentVote::factory()->create([
            'forum_comment_id' => $comment->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($comment->hasVotedBy($user->id));
    }

    /** @test */
    public function it_knows_if_user_has_not_voted()
    {
        $user = User::factory()->create();
        $comment = ForumComment::factory()->create();

        $this->assertFalse($comment->hasVotedBy($user->id));
    }
}
