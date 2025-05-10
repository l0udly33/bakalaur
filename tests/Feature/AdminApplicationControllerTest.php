<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TrainerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_application_view_if_user_has_application()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        TrainerApplication::factory()->create(['user_id' => $user->id]);  // Sukuriama paraiška

        $this->actingAs($admin); // ← būtina

        $response = $this->get("/admin/applications/{$user->id}");

        $response->assertStatus(200); // Tikimasi, kad atsakymas bus 200
        $response->assertViewIs('admin.applications.view');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_redirects_back_if_user_has_no_application()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();  // Sukuriamas vartotojas be paraiškos

        $this->actingAs($admin);

        $response = $this->get("/admin/applications/{$user->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Naudotojas nepateikęs paraiškos.');
    }

    /** @test */
    public function it_approves_the_application()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'pending']);

        $this->actingAs($admin); // ← būtina

        $response = $this->post("/admin/applications/{$user->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Paraiška patvirtinta.');
        $this->assertEquals('trainer', $user->fresh()->role);
    }

    /** @test */
    /** @test */
    public function it_rejects_and_deletes_the_application()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        TrainerApplication::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin);

        $response = $this->post("/admin/applications/{$user->id}/reject");

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Paraiška atmesta.');
        $this->assertDatabaseMissing('trainer_applications', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_saves_admin_notes_to_application()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        TrainerApplication::factory()->create(['user_id' => $user->id]);

        $this->actingAs($admin); // ← būtina

        $response = $this->post("/admin/applications/{$user->id}/notes", [
            'notes' => 'Tai yra pastaba.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Pastabos išsaugotos.');

        $this->assertEquals('Tai yra pastaba.', $user->trainerApplication->fresh()->admin_notes);
    }
}
