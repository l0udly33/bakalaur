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
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order1 = Order::factory()->create(['trainer_id' => $trainer->id]);
        $order2 = Order::factory()->create(['trainer_id' => $trainer->id]);

        $this->actingAs($trainer);

        $response = $this->get(route('trainer.orders'));

        $response->assertStatus(200);
        $response->assertViewHas('orders'); 
        $orders = $response->viewData('orders');
        $this->assertCount(2, $orders); 
    }

    /** @test */
    public function it_displays_order_details_if_trainer_is_authorized()
    {
       
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id]);

       
        $this->actingAs($trainer);

        
        $response = $this->get(route('trainer.orders.show', $order->id));

        
        $response->assertStatus(200);
        $response->assertViewHas('order'); 
        $response->assertViewHas('chats'); 
    }

    /** @test */
    public function it_prevents_unauthorized_trainer_from_viewing_order_details()
    {
        
        $trainer1 = User::factory()->create();
        $trainer2 = User::factory()->create();
        $trainerProfile1 = TrainerProfile::factory()->create(['user_id' => $trainer1->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer1->id]);

        
        $this->actingAs($trainer2);

        
        $response = $this->get(route('trainer.orders.show', $order->id));

        
        $response->assertStatus(403);
    }

    /** @test */
    public function it_updates_order_status()
    {
        
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'pending']);

        
        $this->actingAs($trainer);

        
        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'completed',
        ]);

       
        $response->assertRedirect();
        $response->assertSessionHas('success', 'Užsakymo statusas atnaujintas.');

        $order->refresh();

        $this->assertEquals('completed', $order->status);
    }

    /** @test */
    public function it_prevents_status_update_if_order_is_already_completed_or_canceled()
    {
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'completed']);

        $this->actingAs($trainer);

        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'canceled',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Šio užsakymo statuso keisti negalima.');
    }

    /** @test */
    public function it_adds_balance_to_trainer_when_order_is_completed()
    {
        $trainer = User::factory()->create();
        $trainerProfile = TrainerProfile::factory()->create(['user_id' => $trainer->id]);
        $order = Order::factory()->create(['trainer_id' => $trainer->id, 'status' => 'pending', 'price' => 100]);

        $this->actingAs($trainer);

        $initialBalance = $trainer->balance;

        $response = $this->post(route('trainer.orders.status', $order->id), [
            'status' => 'completed',
        ]);

        $trainer->refresh();

        $this->assertEquals($initialBalance + 100, $trainer->balance);
    }
}


?>
