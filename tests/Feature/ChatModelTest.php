<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Chat;
use App\Models\User;
use App\Models\Order;

class ChatModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_chat_record()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create();

        $chat = Chat::create([
            'sender_id' => $user->id,
            'order_id' => $order->id,
            'message' => 'Test message',
        ]);

        $this->assertDatabaseHas('chats', [
            'sender_id' => $user->id,
            'order_id' => $order->id,
            'message' => 'Test message',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_sender()
    {
        $user = User::factory()->create();

        $chat = Chat::factory()->create([
            'sender_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $chat->sender);
        $this->assertEquals($user->id, $chat->sender->id);
    }

    /** @test */
    public function it_belongs_to_an_order()
    {
        $order = Order::factory()->create();

        $chat = Chat::factory()->create([
            'order_id' => $order->id,
        ]);

        $this->assertInstanceOf(Order::class, $chat->order);
        $this->assertEquals($order->id, $chat->order->id);
    }

    /** @test */
    public function it_can_get_user_relation()
    {
        $user = User::factory()->create();

        $chat = Chat::factory()->create([
            'sender_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $chat->user);
        $this->assertEquals($user->id, $chat->user->id);
    }
}
