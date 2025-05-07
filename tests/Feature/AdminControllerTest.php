<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_all_users_with_sorting_by_role()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user1 = User::factory()->create(['role' => 'trainer']);
        $user2 = User::factory()->create(['role' => 'user']);

        $this->actingAs($admin); // Prisijungiame kaip admin

        // Patikriname, kad vartotojai rodomi
        $response = $this->get('/admin?sort=role');
        $response->assertStatus(200);
        $response->assertSee($user1->name);
        $response->assertSee($user2->name);
        $response->assertSee($admin->name);
    }

    /** @test */
    public function it_shows_the_edit_form_for_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        // Patikriname, kad atidaro redagavimo formą
        $response = $this->get("/admin/users/{$user->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.edit');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_updates_user_info()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        // Atnaujiname vartotojo duomenis
        $response = $this->put("/admin/users/{$user->id}", [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com',
            'role' => 'trainer',
            'admin_notes' => 'Some admin notes',
        ]);

        // Patikriname, ar vartotojo duomenys buvo atnaujinti
        $response->assertRedirect(route('admin'));
        $response->assertSessionHas('success', 'Naudotojas atnaujintas sėkmingai.');

        $user->refresh();

        $this->assertEquals('Updated User', $user->name);
        $this->assertEquals('updateduser@example.com', $user->email);
        $this->assertEquals('trainer', $user->role);
        $this->assertEquals('Some admin notes', $user->admin_notes);
    }

    /** @test */
    public function it_validates_user_update_fields()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $this->actingAs($admin);

        // Bandome atnaujinti su neteisingais duomenimis
        $response = $this->put("/admin/users/{$user->id}", [
            'name' => '',
            'email' => 'not-an-email',
            'role' => 'invalidrole',
        ]);

        // Patikriname, kad atgal grįžta klaidų žinutės
        $response->assertSessionHasErrors(['name', 'email', 'role']);
    }
}
