<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_registration_form()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('register');
    }

    /** @test */
    public function it_registers_a_user_with_valid_data()
    {
        $response = $this->post('/register', [
            'name' => 'Jonas',
            'email' => 'jonas@example.com',
            'password' => 'slaptazodis',
            'password_confirmation' => 'slaptazodis',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'jonas@example.com',
            'name' => 'Jonas',
            'role' => 'user',
        ]);
    }

    /** @test */
    public function it_requires_all_fields()
    {
        $response = $this->post('/register', []);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertGuest();
    }

    /** @test */
    public function it_requires_unique_email()
    {
        User::factory()->create(['email' => 'a@example.com']);

        $response = $this->post('/register', [
            'name' => 'Jonas',
            'email' => 'a@example.com',
            'password' => 'slaptazodis',
            'password_confirmation' => 'slaptazodis',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function it_requires_password_confirmation_to_match()
    {
        $response = $this->post('/register', [
            'name' => 'Jonas',
            'email' => 'test@example.com',
            'password' => 'slaptazodis',
            'password_confirmation' => 'nesutampa',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }
}
