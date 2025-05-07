<?php

namespace Tests\Feature;

use App\Models\TrainerProfile;
use App\Models\User;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_trainer_profile()
    {
        $user = User::factory()->create();

        $profile = TrainerProfile::create([
            'user_id' => $user->id,
            'profile_picture' => null,
            'description' => 'Labai geras treneris',
            'languages' => json_encode(['Lietuvių', 'Anglų']),
            'rank' => 'Radiant',
            'pricing' => ['hour1' => 20, 'hour2' => 30],
            'availability' => ['days' => 'Pirmadienis 10-14'],
        ]);

        $this->assertDatabaseHas('trainer_profiles', [
            'user_id' => $user->id,
            'description' => 'Labai geras treneris',
            'rank' => 'Radiant',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $profile = TrainerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $profile->user);
        $this->assertEquals($user->id, $profile->user->id);
    }

    /** @test */
    public function it_has_reviews()
    {
        $profile = TrainerProfile::factory()->create();
        $review = Review::factory()->create([
            'trainer_profile_id' => $profile->id,
        ]);

        $this->assertTrue($profile->reviews->contains($review));
    }

    /** @test */
    public function it_casts_pricing_and_availability_to_array()
    {
        $profile = TrainerProfile::factory()->create([
            'pricing' => ['hour1' => 25],
            'availability' => ['days' => 'Antradienis'],
        ]);

        $this->assertIsArray($profile->pricing);
        $this->assertIsArray($profile->availability);
        $this->assertEquals(25, $profile->pricing['hour1']);
        $this->assertEquals('Antradienis', $profile->availability['days']);
    }

}
