<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TrainerProfile;
use App\Models\TrainerApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'trainer',
            'balance' => 50.00,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'user@example.com',
            'name' => 'Test User',
        ]);
    }

    /** @test */
    public function it_has_a_trainer_profile()
    {
        $user = User::factory()->create();
        $profile = TrainerProfile::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(TrainerProfile::class, $user->trainerProfile);
        $this->assertEquals($profile->id, $user->trainerProfile->id);
    }

    /** @test */
    public function it_has_a_trainer_application()
    {
        $user = User::factory()->create();
        $application = TrainerApplication::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(TrainerApplication::class, $user->trainerApplication);
        $this->assertEquals($application->id, $user->trainerApplication->id);
    }
}
