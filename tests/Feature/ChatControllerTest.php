<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_chat_for_order()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        $this->actingAs($user);

        $response = $this->get(route('chat.show', $order->id));

        $response->assertStatus(200);
        $response->assertViewHas('order', $order);
    }

    /** @test */
    public function it_shows_forbidden_if_user_is_not_part_of_the_order()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        $otherUser = User::factory()->create();

        $this->actingAs($otherUser);

        $response = $this->get(route('chat.show', $order->id));

        $response->assertStatus(403);
    }

    /** @test */
    public function it_stores_a_message_in_chat()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        $this->actingAs($user);

        $response = $this->post(route('chat.store', $order->id), [
            'message' => 'Testinė žinutė'
        ]);

        $response->assertRedirect(route('chat.show', $order->id));

        $this->assertDatabaseHas('chats', [
            'order_id' => $order->id,
            'message' => 'Testinė žinutė',
            'sender_id' => $user->id
        ]);
    }

    /** @test */
    public function it_forbids_non_participants_from_sending_messages()
    {
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        $otherUser = User::factory()->create();

        $this->actingAs($otherUser);

        $response = $this->post(route('chat.store', $order->id), [
            'message' => 'Neautorizuota žinutė'
        ]);

        $response->assertStatus(403);
    }
}


?>
