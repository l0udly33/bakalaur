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
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $this->actingAs($trainer);

        $response = $this->get(route('trainer.profile.edit'));

        $response->assertStatus(200);
        $response->assertViewHas('profile');
    }

    /** @test */
    public function it_updates_trainer_profile()
    {
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        $this->actingAs($trainer);

        $profileData = [
            'description' => 'New Description',
            'languages' => 'English, Spanish',
            'rank' => 'Gold',
            'pricing' => [['hours' => 2, 'price' => 100]],
            'availability' => ['monday' => '10:00-12:00'],
            'achievements' => [['place' => 1, 'text' => 'Top Trainer']],
            'free_trial' => true,
        ];

        $response = $this->post(route('trainer.profile.update'), $profileData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Profilis atnaujintas');

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
        $user = User::factory()->create();

        $response = $this->get(route('trainer.profile.show', $user->id));

        $response->assertStatus(404);
    }


}

?>
