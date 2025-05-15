<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TrainerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_application_form_if_no_application_exists()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('trainer.application'));

        $response->assertStatus(200);
        $response->assertViewIs('trainer.apply');
    }

    /** @test */
    public function it_redirects_if_user_has_already_submitted_an_application()
    {
        $user = User::factory()->create();
        TrainerApplication::create([
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('trainer.application'));

        $response->assertRedirect(route('user.statistics'));
        $response->assertSessionHas('error', 'Jūs esate pateikę paraišką.');
    }

    /** @test */
    public function it_submits_the_application_with_valid_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('trainer.application.submit'), [
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        $this->assertDatabaseHas('trainer_applications', [
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        $response->assertRedirect(route('user.statistics'));
        $response->assertSessionHas('success', 'Prašymas išsiųstas.');
    }

    /** @test */
    public function it_validates_application_data()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('trainer.application.submit'), [
            'full_name' => '', 
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        $response->assertSessionHasErrors('full_name');
    }
}


?>
