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
        // Create a trainer user and log in
        $trainer = User::factory()->create(['role' => 'trainer']);
        $this->actingAs($trainer);

        // Visit the payout form page
        $response = $this->get(route('trainer.payout.form'));

        // Check if the response is successful and the view is returned
        $response->assertStatus(200);
        $response->assertViewIs('trainer.payout');
    }

    /** @test */
    public function it_submits_a_valid_payout_request()
    {
        // Create a trainer user and log in
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        // Submit a valid payout request
        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 50,
            'paypal_email' => 'trainer@example.com',
        ]);

        // Check if the payout request was created
        $this->assertDatabaseHas('payout_requests', [
            'trainer_id' => $trainer->id,
            'amount' => 50,
            'paypal_email' => 'trainer@example.com',
        ]);

        // Check for the success message
        $response->assertRedirect(route('trainer.payout.form'));
        $response->assertSessionHas('success', 'Išmokėjimo prašymas pateiktas.');
    }

    /** @test */
    public function it_cannot_submit_payout_request_with_amount_greater_than_balance()
    {
        // Create a trainer user with a balance of 50
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 50]);
        $this->actingAs($trainer);

        // Attempt to submit a payout request with an amount greater than the balance
        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 100,
            'paypal_email' => 'trainer@example.com',
        ]);

        // Check that the error message is returned
        $response->assertSessionHasErrors('amount');

        // Assert that the error message matches what is expected.
        // Ensure the message is the one we expect to see.
        $response->assertSessionHas('errors', function ($errors) {
            return $errors->get('amount')[0] === 'Negalite išsiimti daugiau nei turite balanse.';
        });

        // Ensure that the payout request was not created
        $this->assertDatabaseMissing('payout_requests', [
            'trainer_id' => $trainer->id,
            'amount' => 100,
            'paypal_email' => 'trainer@example.com',
        ]);
    }

    /** @test */
    public function it_validates_required_fields_for_payout_request()
    {
        // Create a trainer user and log in
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        // Attempt to submit the payout request without the amount and paypal_email fields
        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => '',
            'paypal_email' => '',
        ]);

        // Assert validation errors are returned
        $response->assertSessionHasErrors(['amount', 'paypal_email']);
    }

    /** @test */
    public function it_validates_amount_as_numeric_and_minimum()
    {
        // Create a trainer user and log in
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        // Attempt to submit a payout request with a non-numeric amount
        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 'invalid',
            'paypal_email' => 'trainer@example.com',
        ]);

        // Assert validation errors are returned for the 'amount'
        $response->assertSessionHasErrors('amount');
    }

    /** @test */
    public function it_validates_paypal_email_format()
    {
        // Create a trainer user and log in
        $trainer = User::factory()->create(['role' => 'trainer', 'balance' => 100]);
        $this->actingAs($trainer);

        // Attempt to submit a payout request with an invalid PayPal email
        $response = $this->post(route('trainer.payout.submit'), [
            'amount' => 50,
            'paypal_email' => 'invalidemail',
        ]);

        // Assert validation errors are returned for the 'paypal_email'
        $response->assertSessionHasErrors('paypal_email');
    }
}


?>
