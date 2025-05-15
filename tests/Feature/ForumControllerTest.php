<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\ForumPost;
use App\Models\ForumComment;
use App\Models\ForumPostVote;
use App\Models\ForumCommentVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ForumControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_forum_posts()
    {
        $user = User::factory()->create();
        $posts = ForumPost::factory(3)->create(['user_id' => $user->id]);

        $this->actingAs($user);

        $response = $this->get(route('forum.index'));

        $response->assertStatus(200);
        $response->assertViewHas('posts', $posts);
    }

    /** @test */
    public function it_creates_forum_post()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('forum.store'), [
            'title' => 'Testinis įrašas',
            'body' => 'Tai yra testinio įrašo turinys.'
        ]);

        $response->assertRedirect(route('forum.index'));
        $this->assertDatabaseHas('forum_posts', [
            'title' => 'Testinis įrašas',
            'body' => 'Tai yra testinio įrašo turinys.'
        ]);
    }


    /** @test */
    public function it_shows_forum_post()
    {
        $post = ForumPost::factory()->create();
        $comments = ForumComment::factory(3)->create(['forum_post_id' => $post->id]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('forum.show', $post->id));

        $response->assertStatus(200);
        $response->assertViewHas('post', $post);
        $response->assertViewHas('comments', $comments);
    }

    /** @test */
    public function it_comments_on_forum_post()
    {
        $post = ForumPost::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('forum.comment', $post->id), [
            'comment' => 'Testinis komentaras'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_comments', [
            'forum_post_id' => $post->id,
            'comment' => 'Testinis komentaras',
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_upvotes_forum_post()
    {
        $post = ForumPost::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('forum.upvote', $post->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_post_votes', [
            'forum_post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_upvotes_forum_comment()
    {
        $post = ForumPost::factory()->create();
        $comment = ForumComment::factory()->create(['forum_post_id' => $post->id]);
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('forum.comment.upvote', $comment->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('forum_comment_votes', [
            'forum_comment_id' => $comment->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_allows_admin_to_edit_forum_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $post = ForumPost::factory()->create();

        $this->actingAs($admin);

        $response = $this->get(route('forum.edit', $post->id));

        $response->assertStatus(200);
        $response->assertViewHas('post', $post);
    }

    /** @test */
    public function it_updates_forum_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $post = ForumPost::factory()->create();

        $this->actingAs($admin);

        $response = $this->put(route('forum.update', $post->id), [
            'title' => 'Atnaujintas įrašas',
            'body' => 'Atnaujintas įrašo turinys'
        ]);

        $response->assertRedirect(route('forum.show', $post->id));
        $this->assertDatabaseHas('forum_posts', [
            'id' => $post->id,
            'title' => 'Atnaujintas įrašas',
            'body' => 'Atnaujintas įrašo turinys'
        ]);
    }

    /** @test */
    public function it_forbids_non_admin_to_edit_forum_post()
    {
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('forum.edit', $post->id));

        $response->assertStatus(403);
    }

}



?>
