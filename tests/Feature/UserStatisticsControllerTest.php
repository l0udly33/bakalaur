<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UserStatisticsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function get_statistika_route_requires_authentication()
    {
        $response = $this->get('/statistika');
        $response->assertRedirect('/login'); 
    }

    /** @test */
    public function post_statistika_requires_authentication()
    {
        $response = $this->post('/statistika', []);
        $response->assertRedirect('/login'); 
    }

    /** @test */
    public function get_statistika_returns_view_for_authenticated_user()
    {
        $this->authenticate();

        $response = $this->get('/statistika');

        $response->assertStatus(200);
        $response->assertViewIs('statistics.index');
    }

    /** @test */
    public function post_statistika_validates_required_fields()
    {
        $this->authenticate();

        $response = $this->post('/statistika', []); 

        $response->assertSessionHasErrors(['username', 'tag']);
    }

    /** @test */
    public function post_statistika_returns_expected_data_in_view()
    {
        $this->authenticate();

        $username = 'TestUser';
        $tag = 'EUW';

        $response = $this->post('/statistika', [
            'username' => $username,
            'tag' => $tag,
        ]);

        $riotID = rawurlencode("{$username}#{$tag}");
        $expectedUrl = "https://tracker.gg/valorant/profile/riot/{$riotID}/overview?platform=pc&playlist=competitive&season=16118998-4705-5813-86dd-0292a2439d90";

        $response->assertStatus(200);
        $response->assertViewIs('statistics.index');
        $response->assertViewHasAll([
            'username' => $username,
            'tag' => $tag,
            'trackerUrl' => $expectedUrl
        ]);
    }
}
