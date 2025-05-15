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
        
        $user = User::factory()->create();
        $this->actingAs($user);

        
        $response = $this->get(route('balance.add'));

       
        $response->assertStatus(200);
        $response->assertViewIs('balance.add');
    }

    /** @test */
    public function it_adds_balance()
    {
        
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        
        $response = $this->post(route('balance.add.post'), ['amount' => 50]);

        
        $response->assertJson(['success' => true, 'new_balance' => 150]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'balance' => 150]);
    }

    /** @test */
    public function it_shows_the_withdraw_balance_form()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('balance.withdraw'));

        $response->assertStatus(200);
        $response->assertViewIs('balance.withdraw');
    }

    /** @test */
    public function it_withdraws_balance_successfully()
    {
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        $response = $this->post(route('balance.withdraw.post'), ['amount' => 50]);

        $response->assertJson(['success' => true, 'new_balance' => 50]);

        $this->assertDatabaseHas('users', ['id' => $user->id, 'balance' => 50]);
    }

    /** @test */
    public function it_returns_error_if_balance_is_insufficient()
    {
        $user = User::factory()->create(['balance' => 100]);
        $this->actingAs($user);

        $response = $this->post(route('balance.withdraw.post'), ['amount' => 200]);

        $response->assertJson(['success' => false, 'error' => 'Nepakanka lėšų.']);
    }


}



?>
