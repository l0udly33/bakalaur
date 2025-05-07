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
        // Sukuriame treniruotę ir užsakymus
        $trainer = User::factory()->create();
        $orders = Order::factory(3)->create(['trainer_id' => $trainer->id]);

        // Prisijungiame kaip admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Atidarome trenerio užsakymų puslapį
        $response = $this->get(route('admin.trainer.orders', $trainer->id));

        // Patikriname, kad atsiliepimai ir užsakymai rodomi
        $response->assertStatus(200);
        $response->assertViewHas('trainer', $trainer);
        $response->assertViewHas('orders', $orders);
    }

    /** @test */
    public function it_displays_chat_for_order()
    {
        // Sukuriame užsakymą ir žinutes
        $order = Order::factory()->create();
        $messages = \App\Models\Chat::factory(5)->create(['order_id' => $order->id]);

        // Prisijungiame kaip admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Atidarome užsakymo pokalbių puslapį
        $response = $this->get(route('admin.chat.view', $order->id));

        // Patikriname, kad žinutės ir užsakymo informacija rodomi
        $response->assertStatus(200);
        $response->assertViewHas('order', $order);
        $response->assertViewHas('messages', $messages);
    }

    /** @test */
    public function it_sends_a_message_in_chat()
    {
        // Sukuriame užsakymą
        $order = Order::factory()->create();

        // Prisijungiame kaip admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Išsiunčiame žinutę
        $response = $this->post(route('admin.chat.send', $order->id), [
            'message' => 'Testinė žinutė',
        ]);

        // Patikriname, kad žinutė buvo išsiųsta
        $response->assertRedirect(route('admin.chat.view', $order->id));
        $response->assertSessionHas('success', 'Žinutė išsiųsta.');

        // Patikriname, kad žinutė buvo įrašyta į duomenų bazę
        $this->assertDatabaseHas('chats', [
            'order_id' => $order->id,
            'message' => 'Testinė žinutė',
            'sender_id' => $admin->id,
        ]);
    }


}
