<?php

namespace Tests\Feature;

use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPayoutControllerTest extends TestCase
{
    use RefreshDatabase;

   


    /** @test */
    public function it_updates_payout_status_to_completed()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 1000]);
        $payout = PayoutRequest::factory()->create(['trainer_id' => $trainer->id, 'amount' => 200, 'status' => 'pending']);

        $this->actingAs($admin);

        $response = $this->post('/admin/payouts/' . $payout->id . '/status', [
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Išmokėjimo statusas atnaujintas.');
        $this->assertEquals('completed', $payout->fresh()->status);
        $this->assertEquals(800, $trainer->fresh()->balance);  
    }

    /** @test */
    public function it_shows_error_if_trainer_has_insufficient_balance_for_payout()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $payout = PayoutRequest::factory()->create(['trainer_id' => $trainer->id, 'amount' => 200, 'status' => 'pending']);

        $this->actingAs($admin);

        $response = $this->post('/admin/payouts/' . $payout->id . '/status', [
            'status' => 'completed',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['message' => 'Nepakanka lėšų.']);
        $this->assertEquals('pending', $payout->fresh()->status);  
    }

    /** @test */
    public function it_requires_valid_status_when_updating_payout()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $trainer = User::factory()->create(['role' => 'trainer']);
        $payout = PayoutRequest::factory()->create(['trainer_id' => $trainer->id, 'status' => 'pending']);

        $this->actingAs($admin);

        $response = $this->post('/admin/payouts/' . $payout->id . '/status', [
            'status' => 'invalid_status',  
        ]);

        $response->assertSessionHasErrors('status');
    }
}
