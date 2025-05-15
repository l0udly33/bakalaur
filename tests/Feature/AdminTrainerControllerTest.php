<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTrainerControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_trainer_orders()
    {
        
        $trainer = User::factory()->create();
        $orders = Order::factory(3)->create(['trainer_id' => $trainer->id]);

       
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        
        $response = $this->get(route('admin.trainer.orders', $trainer->id));

        
        $response->assertStatus(200);
        $response->assertViewHas('trainer', $trainer);
        $response->assertViewHas('orders', $orders);
    }

    /** @test */
    public function it_displays_chat_for_order()
    {
        
        $order = Order::factory()->create();
        $messages = \App\Models\Chat::factory(5)->create(['order_id' => $order->id]);

        
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        
        $response = $this->get(route('admin.chat.view', $order->id));

        
        $response->assertStatus(200);
        $response->assertViewHas('order', $order);
        $response->assertViewHas('messages', $messages);
    }

    /** @test */
    public function it_sends_a_message_in_chat()
    {
        
        $order = Order::factory()->create();

        
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        
        $response = $this->post(route('admin.chat.send', $order->id), [
            'message' => 'Testinė žinutė',
        ]);

        
        $response->assertRedirect(route('admin.chat.view', $order->id));
        $response->assertSessionHas('success', 'Žinutė išsiųsta.');

        
        $this->assertDatabaseHas('chats', [
            'order_id' => $order->id,
            'message' => 'Testinė žinutė',
            'sender_id' => $admin->id,
        ]);
    }


}
