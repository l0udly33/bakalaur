<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TrainerProfile;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class TrainerProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_the_trainer_profile_edit_form()
    {
        // Create a trainer and log in
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        // Act as the trainer
        $this->actingAs($trainer);

        // Visit the edit profile page
        $response = $this->get(route('trainer.profile.edit'));

        // Assert that the response is successful and the trainer profile is passed to the view
        $response->assertStatus(200);
        $response->assertViewHas('profile');
    }

    /** @test */
    public function it_updates_trainer_profile()
    {
        // Create a trainer and log in
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        // Act as the trainer
        $this->actingAs($trainer);

        // Prepare data for profile update
        $profileData = [
            'description' => 'New Description',
            'languages' => 'English, Spanish',
            'rank' => 'Gold',
            'pricing' => [['hours' => 2, 'price' => 100]],
            'availability' => ['monday' => '10:00-12:00'],
            'achievements' => [['place' => 1, 'text' => 'Top Trainer']],
            'free_trial' => true,
        ];

        // Perform profile update
        $response = $this->post(route('trainer.profile.update'), $profileData);

        // Assert that the response is a redirect back
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profilis atnaujintas');

        // Refresh the trainer's profile and check if the values are updated
        $trainer->refresh();
        $this->assertEquals($profileData['description'], $trainer->trainerProfile->description);
        $this->assertEquals($profileData['languages'], $trainer->trainerProfile->languages);
        $this->assertEquals($profileData['rank'], $trainer->trainerProfile->rank);
        $this->assertEquals($profileData['pricing'], $trainer->trainerProfile->pricing);
        $this->assertEquals($profileData['availability'], $trainer->trainerProfile->availability);
        $this->assertEquals(json_encode($profileData['achievements']), $trainer->trainerProfile->achievements);
    }

    /** @test */
    public function it_shows_404_if_trainer_profile_is_not_found()
    {
        // Create a user who is not a trainer
        $user = User::factory()->create();

        // Try to view the trainer profile of a non-trainer user
        $response = $this->get(route('trainer.profile.show', $user->id));

        // Assert that the response is 404
        $response->assertStatus(404);
    }


}

?>
