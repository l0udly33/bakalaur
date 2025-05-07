<?php

namespace Tests\Feature;

use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use App\Models\TrainerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_review()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create();

        $review = Review::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'trainer_profile_id' => $trainerProfile->id,
            'rating' => 5,
            'comment' => 'Puikus darbas!',
        ]);

        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id,
            'user_id' => $user->id,
            'trainer_profile_id' => $trainerProfile->id,
            'rating' => 5,
            'comment' => 'Puikus darbas!',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $review = Review::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $review->user);
        $this->assertEquals($user->id, $review->user->id);
    }

    /** @test */
    public function it_belongs_to_an_order()
    {
        $order = Order::factory()->create();

        $review = Review::factory()->create([
            'order_id' => $order->id,
        ]);

        $this->assertInstanceOf(Order::class, $review->order);
        $this->assertEquals($order->id, $review->order->id);
    }

    /** @test */
    public function it_belongs_to_a_trainer_profile()
    {
        $profile = TrainerProfile::factory()->create();

        $review = Review::factory()->create([
            'trainer_profile_id' => $profile->id,
        ]);

        $this->assertInstanceOf(TrainerProfile::class, $review->trainerProfile);
        $this->assertEquals($profile->id, $review->trainerProfile->id);
    }
}
