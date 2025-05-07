<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\TrainerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_order_with_free_trial()
    {
        // Set up a trainer and user
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        $profile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        // Simulate a free trial order creation
        $this->actingAs($user);

        $response = $this->post(route('user-orders.store'), [
            'trainer_id' => $trainer->id,
            'selected_option' => 'free_trial',
            'description' => 'Free trial session'
        ]);

        // Assert order created successfully
        $response->assertRedirect(route('orders.user'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'price' => 0,
            'hours' => 0.25,
        ]);
    }

    /** @test */
    public function it_creates_order_with_paid_option()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        $profile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);

        // Adding a pricing option to the trainer profile
        $profile->update([
            'pricing' => [
                ['hours' => 1, 'price' => 50],
            ]
        ]);

        $this->actingAs($user);

        // Submit the order with the selected pricing option
        $response = $this->post(route('user-orders.store'), [
            'trainer_id' => $trainer->id,
            'selected_option' => 0,  // The index of the pricing option
            'description' => 'Paid session'
        ]);

        // Assert that the order is created
        $response->assertRedirect(route('orders.user'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'price' => 50,
            'hours' => 1,
        ]);
    }


    /** @test */
    public function it_deletes_review_if_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();
        $trainer = User::factory()->create(['role' => 'trainer']);
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);
        $review = Review::factory()->create(['order_id' => $order->id, 'user_id' => $user->id]);

        $this->actingAs($admin);

        $response = $this->delete(route('admin.reviews.destroy', $review->id));

        // Assert that the review is deleted
        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }
}


?>
