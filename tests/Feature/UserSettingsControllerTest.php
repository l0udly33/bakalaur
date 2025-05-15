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
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('user.settings'));

        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_allows_updating_user_name_and_email()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->put(route('user.settings.update'), [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Profilis atnaujintas sėkmingai.');

        $user->refresh();
        $this->assertEquals('New Name', $user->name);
        $this->assertEquals('newemail@example.com', $user->email);
    }

    /** @test */
    public function it_does_not_allow_duplicate_email()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $this->actingAs($user1);

        $response = $this->put(route('user.settings.update'), [
            'name' => 'New Name',
            'email' => $user2->email, // Duplicate email
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_allows_updating_password()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->put(route('user.settings.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword', 
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Profilis atnaujintas sėkmingai.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    /** @test */
    public function it_does_not_update_password_if_not_confirmed()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->put(route('user.settings.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'newpassword', 
            'password_confirmation' => 'differentpassword', 
        ]);

        
        $response->assertRedirect();
        $response->assertSessionHasErrors('password');
    }
}
