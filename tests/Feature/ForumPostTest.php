<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumPostVote;
use App\Models\ForumComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_post()
    {
        $user = User::factory()->create();

        $post = ForumPost::create([
            'title' => 'Test Title',
            'body' => 'Test Body',
            'user_id' => $user->id,
            'pinned' => true,
            'upvotes' => 0,
        ]);

        $this->assertDatabaseHas('forum_posts', [
            'title' => 'Test Title',
            'body' => 'Test Body',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $post = ForumPost::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    /** @test */
    public function it_has_comments()
    {
        $post = ForumPost::factory()->create();
        $comment = ForumComment::factory()->create([
            'forum_post_id' => $post->id,
        ]);

        $this->assertTrue($post->comments->contains($comment));
    }

    /** @test */
    public function it_has_votes()
    {
        $post = ForumPost::factory()->create();
        $vote = ForumPostVote::factory()->create([
            'forum_post_id' => $post->id,
        ]);

        $this->assertTrue($post->votes->contains($vote));
    }

    /** @test */
    public function it_knows_if_user_has_voted()
    {
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        ForumPostVote::factory()->create([
            'forum_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertTrue($post->hasVotedBy($user->id));
    }

    /** @test */
    public function it_knows_if_user_has_not_voted()
    {
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        $this->assertFalse($post->hasVotedBy($user->id));
    }
}
