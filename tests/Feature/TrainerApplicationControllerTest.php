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
        // Create a user without a trainer application
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Request the application form
        $response = $this->get(route('trainer.application'));

        // Assert that the form page is returned
        $response->assertStatus(200);
        $response->assertViewIs('trainer.apply');
    }

    /** @test */
    public function it_redirects_if_user_has_already_submitted_an_application()
    {
        // Create a user with a trainer application
        $user = User::factory()->create();
        TrainerApplication::create([
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        // Act as the user
        $this->actingAs($user);

        // Request the application form
        $response = $this->get(route('trainer.application'));

        // Assert that the user is redirected with an error message
        $response->assertRedirect(route('user.statistics'));
        $response->assertSessionHas('error', 'Jūs esate pateikę paraišką.');
    }

    /** @test */
    public function it_submits_the_application_with_valid_data()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Send valid application data
        $response = $this->post(route('trainer.application.submit'), [
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        // Assert that the application is saved in the database
        $this->assertDatabaseHas('trainer_applications', [
            'user_id' => $user->id,
            'full_name' => 'John Doe',
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        // Assert that the user is redirected to the statistics page with a success message
        $response->assertRedirect(route('user.statistics'));
        $response->assertSessionHas('success', 'Prašymas išsiųstas.');
    }

    /** @test */
    public function it_validates_application_data()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Send invalid application data (missing required fields)
        $response = $this->post(route('trainer.application.submit'), [
            'full_name' => '', // Missing full name
            'rank' => 'Beginner',
            'age' => 25,
            'experience' => '2 years',
            'motivation' => 'Passion for teaching',
        ]);

        // Assert that validation errors are returned for the full_name field
        $response->assertSessionHasErrors('full_name');
    }
}


?>
