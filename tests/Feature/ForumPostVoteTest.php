<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ForumPost;
use App\Models\ForumPostVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumPostVoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_post_vote()
    {
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        $vote = ForumPostVote::create([
            'forum_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('forum_post_votes', [
            'forum_post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $vote = ForumPostVote::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $vote->user);
        $this->assertEquals($user->id, $vote->user->id);
    }

    /** @test */
    public function it_belongs_to_a_post()
    {
        $post = \App\Models\ForumPost::factory()->create();

        $vote = \App\Models\ForumPostVote::factory()->create([
            'forum_post_id' => $post->id,
        ]);

        $vote->refresh();

        $this->assertInstanceOf(\App\Models\ForumPost::class, $vote->post);
        $this->assertEquals($post->id, $vote->post->id);
    }
}
