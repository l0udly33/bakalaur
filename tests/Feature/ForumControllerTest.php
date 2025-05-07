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
        // Sukuriame naudotoją ir kelis forumo įrašus
        $user = User::factory()->create();
        $posts = ForumPost::factory(3)->create(['user_id' => $user->id]);

        // Prisijungiame kaip vartotojas
        $this->actingAs($user);

        // Užklausos siunčiame forumo įrašų puslapį
        $response = $this->get(route('forum.index'));

        // Patikriname, kad puslapis rodomas teisingai ir kad yra įrašų
        $response->assertStatus(200);
        $response->assertViewHas('posts', $posts);
    }

    /** @test */
    public function it_creates_forum_post()
    {
        // Sukuriame naudotoją
        $user = User::factory()->create();
        $this->actingAs($user);

        // Siunčiame prašymą su naujo forumo įrašo duomenimis
        $response = $this->post(route('forum.store'), [
            'title' => 'Testinis įrašas',
            'body' => 'Tai yra testinio įrašo turinys.'
        ]);

        // Patikriname, kad įrašas buvo sukurtas
        $response->assertRedirect(route('forum.index'));
        $this->assertDatabaseHas('forum_posts', [
            'title' => 'Testinis įrašas',
            'body' => 'Tai yra testinio įrašo turinys.'
        ]);
    }


    /** @test */
    public function it_shows_forum_post()
    {
        // Sukuriame forumo įrašą su komentarais
        $post = ForumPost::factory()->create();
        $comments = ForumComment::factory(3)->create(['forum_post_id' => $post->id]);

        // Prisijungiame kaip vartotojas
        $user = User::factory()->create();
        $this->actingAs($user);

        // Užklausos siunčiame forumo įrašo puslapį
        $response = $this->get(route('forum.show', $post->id));

        // Patikriname, kad atvaizduojami komentarai ir įrašas
        $response->assertStatus(200);
        $response->assertViewHas('post', $post);
        $response->assertViewHas('comments', $comments);
    }

    /** @test */
    public function it_comments_on_forum_post()
    {
        // Sukuriame forumo įrašą
        $post = ForumPost::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        // Siunčiame komentarą
        $response = $this->post(route('forum.comment', $post->id), [
            'comment' => 'Testinis komentaras'
        ]);

        // Patikriname, kad komentaras buvo išsaugotas ir peradresuotas atgal
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
        // Sukuriame forumo įrašą
        $post = ForumPost::factory()->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        // Siunčiame balsą už įrašą
        $response = $this->post(route('forum.upvote', $post->id));

        // Patikriname, kad įrašas buvo balsuotas ir peradresuotas atgal
        $response->assertRedirect();
        $this->assertDatabaseHas('forum_post_votes', [
            'forum_post_id' => $post->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_upvotes_forum_comment()
    {
        // Sukuriame forumo įrašą ir komentarą
        $post = ForumPost::factory()->create();
        $comment = ForumComment::factory()->create(['forum_post_id' => $post->id]);
        $user = User::factory()->create();
        $this->actingAs($user);

        // Siunčiame balsą už komentarą
        $response = $this->post(route('forum.comment.upvote', $comment->id));

        // Patikriname, kad komentaras buvo balsuotas
        $response->assertRedirect();
        $this->assertDatabaseHas('forum_comment_votes', [
            'forum_comment_id' => $comment->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function it_allows_admin_to_edit_forum_post()
    {
        // Sukuriame administratorių ir forumo įrašą
        $admin = User::factory()->create(['role' => 'admin']);
        $post = ForumPost::factory()->create();

        // Prisijungiame kaip admin
        $this->actingAs($admin);

        // Užklausos siunčiame forumo įrašo redagavimo puslapį
        $response = $this->get(route('forum.edit', $post->id));

        // Patikriname, kad redagavimo puslapis yra rodomas
        $response->assertStatus(200);
        $response->assertViewHas('post', $post);
    }

    /** @test */
    public function it_updates_forum_post()
    {
        // Sukuriame administratorių ir forumo įrašą
        $admin = User::factory()->create(['role' => 'admin']);
        $post = ForumPost::factory()->create();

        // Prisijungiame kaip admin
        $this->actingAs($admin);

        // Siunčiame atnaujinto įrašo duomenis
        $response = $this->put(route('forum.update', $post->id), [
            'title' => 'Atnaujintas įrašas',
            'body' => 'Atnaujintas įrašo turinys'
        ]);

        // Patikriname, kad įrašas buvo atnaujintas
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
        // Sukuriame naudotoją ir forumo įrašą
        $user = User::factory()->create();
        $post = ForumPost::factory()->create();

        // Prisijungiame kaip ne admin
        $this->actingAs($user);

        // Bandome pasiekti redagavimo puslapį
        $response = $this->get(route('forum.edit', $post->id));

        // Patikriname, kad gauname 403 klaidą
        $response->assertStatus(403);
    }

}



?>
