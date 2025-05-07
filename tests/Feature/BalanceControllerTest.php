<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class BalanceControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_add_balance_form()
    {
        // Sukuriame naudotoją ir prisijungiame kaip jis
        $user = User::factory()->create();
        $this->actingAs($user);

        // Atidarome 'add balance' puslapį
        $response = $this->get(route('balance.add'));

        // Patikriname, kad puslapis rodomas teisingai
        $response->assertStatus(200);
        $response->assertViewIs('balance.add');
    }

    /** @test */
    public function it_adds_balance()
    {
        // Sukuriame naudotoją ir prisijungiame kaip jis
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        // Siunčiame prašymą pridėti balansą
        $response = $this->post(route('balance.add.post'), ['amount' => 50]);

        // Patikriname, kad balansas buvo pridėtas teisingai
        $response->assertJson(['success' => true, 'new_balance' => 150]);

        // Patikriname, kad duomenys buvo atnaujinti duomenų bazėje
        $this->assertDatabaseHas('users', ['id' => $user->id, 'balance' => 150]);
    }

    /** @test */
    public function it_shows_the_withdraw_balance_form()
    {
        // Sukuriame naudotoją ir prisijungiame kaip jis
        $user = User::factory()->create();
        $this->actingAs($user);

        // Atidarome 'withdraw balance' puslapį
        $response = $this->get(route('balance.withdraw'));

        // Patikriname, kad puslapis rodomas teisingai
        $response->assertStatus(200);
        $response->assertViewIs('balance.withdraw');
    }

    /** @test */
    public function it_withdraws_balance_successfully()
    {
        // Sukuriame naudotoją ir prisijungiame kaip jis
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        // Siunčiame prašymą pašalinti balansą
        $response = $this->post(route('balance.withdraw.post'), ['amount' => 50]);

        // Patikriname, kad balansas buvo sumažintas
        $response->assertJson(['success' => true, 'new_balance' => 50]);

        // Patikriname, kad duomenys buvo atnaujinti duomenų bazėje
        $this->assertDatabaseHas('users', ['id' => $user->id, 'balance' => 50]);
    }

    /** @test */
    public function it_returns_error_if_balance_is_insufficient()
    {
        // Sukuriame naudotoją ir prisijungiame kaip jis
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        // Siunčiame prašymą pašalinti per daug pinigų
        $response = $this->post(route('balance.withdraw.post'), ['amount' => 200]);

        // Patikriname, kad gavome klaidos atsakymą
        $response->assertJson(['success' => false, 'error' => 'Nepakanka lėšų.']);
    }


}



?>
