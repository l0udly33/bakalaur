<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class UserSettingsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_user_settings_page()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Visit the user settings page
        $response = $this->get(route('user.settings'));

        // Assert that the response is successful and the correct user is shown
        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_allows_updating_user_name_and_email()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Send a request to update the user's name and email
        $response = $this->put(route('user.settings.update'), [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        // Assert that the user is redirected back with success message
        $response->assertRedirect();
        $response->assertSessionHas('status', 'Profilis atnaujintas sėkmingai.');

        // Assert that the user's name and email are updated in the database
        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('newemail@example.com', $user->email);
    }

    /** @test */
    public function it_does_not_allow_duplicate_email()
    {
        // Create two users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Act as the first user
        $this->actingAs($user1);

        // Try to update the user's email to the second user's email
        $response = $this->put(route('user.settings.update'), [
            'name' => 'New Name',
            'email' => $user2->email, // Duplicate email
        ]);

        // Assert that the user is redirected back with an error message
        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_allows_updating_password()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Send a request to update the user's password
        $response = $this->put(route('user.settings.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword', // New password
            'password_confirmation' => 'newpassword',
        ]);

        // Assert that the user is redirected back with success message
        $response->assertRedirect();
        $response->assertSessionHas('status', 'Profilis atnaujintas sėkmingai.');

        // Assert that the password has been updated in the database
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    /** @test */
    public function it_does_not_update_password_if_not_confirmed()
    {
        // Create a user
        $user = User::factory()->create();

        // Act as the user
        $this->actingAs($user);

        // Send a request to update the user's password with mismatched confirmation
        $response = $this->put(route('user.settings.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword', // New password
            'password_confirmation' => 'differentpassword', // Mismatched confirmation
        ]);

        // Assert that the user is redirected back with errors
        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
    }
}
