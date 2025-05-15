<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PayoutRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class PayoutControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_payout_form()
    {
        $trainer = User::factory()->create(['role' => 'trainer']);
        $this->actingAs($trainer);

        $response = $this->get(route('trainer.payout.form'));

        $response->assertStatus(200);
        $response->assertViewIs('trainer.payout');
    }

    /** @test */
    public function it_submits_a_valid_payout_request()
    {
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 50,
            'paypal_email' => 'trainer@example.com',
        ]);

        $this->assertDatabaseHas('payout_requests', [
            'trainer_id' => $trainer->id,
            'amount' => 50,
            'paypal_email' => 'trainer@example.com',
        ]);

        $response->assertRedirect(route('trainer.payout.form'));
        $response->assertSessionHas('success', 'Išmokėjimo prašymas pateiktas.');
    }

    /** @test */
    public function it_cannot_submit_payout_request_with_amount_greater_than_balance()
    {
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 50]);
        $this->actingAs($trainer);

        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 100,
            'paypal_email' => 'trainer@example.com',
        ]);

        $response->assertSessionHasErrors('amount');

        
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->get('amount')[0] === 'Negalite išsiimti daugiau nei turite balanse.';
        });

        $this->assertDatabaseMissing('payout_requests', [
            'trainer_id' => $trainer->id,
            'amount' => 100,
            'paypal_email' => 'trainer@example.com',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_for_payout_request()
    {
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => '',
            'paypal_email' => '',
        ]);

        $response->assertSessionHasErrors(['amount', 'paypal_email']);
    }

    /** @test */
    public function it_validates_amount_as_numeric_and_minimum()
    {
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 'invalid',
            'paypal_email' => 'trainer@example.com',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function it_validates_paypal_email_format()
    {
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 50,
            'paypal_email' => 'invalidemail',
        ]);

        $response->assertSessionHasErrors('paypal_email');
    }
}


?>
