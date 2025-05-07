<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\TrainerProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TrainerOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_trainer_orders()
    {
        // Create a trainer and assign an order to them
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order1 = Order::factory()->create(['trainer_id' => $trainer->id]);
        $order2 = Order::factory()->create(['trainer_id' => $trainer->id]);

        // Act as the trainer
        $this->actingAs($trainer);

        // Visit the trainer orders page
        $response = $this->get(route('trainer.orders'));

        // Assert that the orders are displayed correctly
        $response->assertStatus(200);
        $response->assertViewHas('orders'); // Check that the orders are passed to the view
        $orders = $response->viewData('orders');
        $this->assertCount(2, $orders); // Ensure that two orders are returned
    }

    /** @test */
    public function it_displays_order_details_if_trainer_is_authorized()
    {
        // Create a trainer and an order assigned to them
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id]);

        // Act as the trainer
        $this->actingAs($trainer);

        // Visit the order details page
        $response = $this->get(route('trainer.orders.show', $order->id));

        // Assert that the response is successful
        $response->assertStatus(200);
        $response->assertViewHas('order'); // Ensure that the order is passed to the view
        $response->assertViewHas('chats'); // Ensure that chats are loaded
    }

    /** @test */
    public function it_prevents_unauthorized_trainer_from_viewing_order_details()
    {
        // Create two trainers and an order assigned to one of them
        $trainer1 = User::factory()->create();
        $trainer2 = User::factory()->create();
        $trainerProfile1 = TrainerProfile::factory()->create(['user_id' => $trainer1->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer1->id]);

        // Act as a different trainer who doesn't own the order
        $this->actingAs($trainer2);

        // Try to visit the order details page
        $response = $this->get(route('trainer.orders.show', $order->id));

        // Assert that the response is forbidden (403)
        $response->assertStatus(403);
    }

    /** @test */
    public function it_updates_order_status()
    {
        // Create a trainer and an order
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'pending']);

        // Act as the trainer
        $this->actingAs($trainer);

        // Update the status of the order
        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'completed',
        ]);

        // Assert that the response is a redirect back
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Užsakymo statusas atnaujintas.');

        // Refresh the order to check the updated status
        $order->refresh();

        // Assert that the status was successfully updated to 'completed'
        $this->assertEquals('completed', $order->status);
    }

    /** @test */
    public function it_prevents_status_update_if_order_is_already_completed_or_canceled()
    {
        // Create a trainer and an order with completed status
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'completed']);

        // Act as the trainer
        $this->actingAs($trainer);

        // Try to update the status of a completed order
        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'canceled',
        ]);

        // Assert that the response is a redirect with an error message
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Šio užsakymo statuso keisti negalima.');
    }

    /** @test */
    public function it_adds_balance_to_trainer_when_order_is_completed()
    {
        // Create a trainer and an order with a price
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'pending', 'price' => 100]);

        // Act as the trainer
        $this->actingAs($trainer);

        // Save the initial balance
        $initialBalance = $trainer->balance;

        // Update the status of the order to 'completed'
        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'completed',
        ]);

        // Refresh the trainer's data and check if the balance has increased
        $trainer->refresh();

        // Assert that the trainer's balance has increased by the order price
        $this->assertEquals($initialBalance + 100, $trainer->balance);
    }
}


?>
