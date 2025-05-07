<?php

namespace Tests\Feature;

use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PayoutRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_payout_request()
    {
        $trainer = User::factory()->create();

        $payout = PayoutRequest::create([
            'trainer_id' => $trainer->id,
            'amount' => 100.00,
            'paypal_email' => 'trainer@example.com',
        ]);

        $this->assertDatabaseHas('payout_requests', [
            'trainer_id' => $trainer->id,
            'amount' => 100.00,
            'paypal_email' => 'trainer@example.com',
        ]);
    }

    /** @test */
    public function it_belongs_to_a_trainer()
    {
        $trainer = User::factory()->create();

        $payout = PayoutRequest::factory()->create([
            'trainer_id' => $trainer->id,
        ]);

        $payout->refresh();

        $this->assertInstanceOf(User::class, $payout->trainer);
        $this->assertEquals($trainer->id, $payout->trainer->id);
    }
}
