<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Models\Chat;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_order()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'status' => 'pending',
            'description' => 'Test order',
            'price' => 49.99,
            'hours' => 2,
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'trainer_id' => $trainer->id,
            'description' => 'Test order',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $order = Order::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }

    /** @test */
    public function it_belongs_to_a_trainer()
    {
        $trainer = User::factory()->create();

        $order = Order::factory()->create([
            'trainer_id' => $trainer->id,
        ]);

        $this->assertInstanceOf(User::class, $order->trainer);
        $this->assertEquals($trainer->id, $order->trainer->id);
    }

    /** @test */
    public function it_has_chats()
    {
        $order = Order::factory()->create();
        $chat = Chat::factory()->create([
            'order_id' => $order->id,
        ]);

        $this->assertTrue($order->chats->contains($chat));
    }

    /** @test */
    public function it_has_a_review()
    {
        $order = Order::factory()->create();
        $review = Review::factory()->create([
            'order_id' => $order->id,
        ]);

        $this->assertInstanceOf(Review::class, $order->review);
        $this->assertEquals($review->id, $order->review->id);
    }
}
