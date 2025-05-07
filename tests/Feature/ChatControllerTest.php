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
        // Sukuriame užsakymą ir prisijungusį naudotoją
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        // Prisijungiame kaip vartotojas
        $this->actingAs($user);

        // Užklausos siunčiame pokalbių puslapį
        $response = $this->get(route('chat.show', $order->id));

        // Patikriname, kad pokalbis rodomas ir užsakymas teisingas
        $response->assertStatus(200);
        $response->assertViewHas('order', $order);
    }

    /** @test */
    public function it_shows_forbidden_if_user_is_not_part_of_the_order()
    {
        // Sukuriame du naudotojus ir užsakymą
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        // Sukuriame trečią naudotoją, kuris nėra dalis užsakymo
        $otherUser = User::factory()->create();

        // Prisijungiame kaip neautorizuotas naudotojas
        $this->actingAs($otherUser);

        // Užklausos siunčiame pokalbių puslapį
        $response = $this->get(route('chat.show', $order->id));

        // Patikriname, kad gauname 403 klaidą (Forbidden)
        $response->assertStatus(403);
    }

    /** @test */
    public function it_stores_a_message_in_chat()
    {
        // Sukuriame užsakymą ir naudotoją
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        // Prisijungiame kaip vartotojas
        $this->actingAs($user);

        // Siunčiame žinutę
        $response = $this->post(route('chat.store', $order->id), [
            'message' => 'Testinė žinutė'
        ]);

        // Patikriname, kad žinutė buvo išsaugota ir redirect į pokalbių puslapį
        $response->assertRedirect(route('chat.show', $order->id));

        // Patikriname, kad žinutė buvo įrašyta į duomenų bazę
        $this->assertDatabaseHas('chats', [
            'order_id' => $order->id,
            'message' => 'Testinė žinutė',
            'sender_id' => $user->id
        ]);
    }

    /** @test */
    public function it_forbids_non_participants_from_sending_messages()
    {
        // Sukuriame užsakymą ir naudotoją
        $user = User::factory()->create();
        $trainer = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id, 'trainer_id' => $trainer->id]);

        // Sukuriame trečią naudotoją, kuris nėra dalis užsakymo
        $otherUser = User::factory()->create();

        // Prisijungiame kaip neautorizuotas naudotojas
        $this->actingAs($otherUser);

        // Bandome išsiųsti žinutę
        $response = $this->post(route('chat.store', $order->id), [
            'message' => 'Neautorizuota žinutė'
        ]);

        // Patikriname, kad gavome 403 klaidą (Forbidden)
        $response->assertStatus(403);
    }
}


?>
