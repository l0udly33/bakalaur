<?php

namespace Tests\Feature;

use App\Models\BadWord;
use App\Models\Review;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    // Testas, kad filtruojame blogus atsiliepimus pagal netinkamus žodžius
    /** @test */
    public function it_filters_bad_reviews()
    {
        // Sukuriame užsakymą
        $order = Order::factory()->create();

        // Sukuriame atsiliepimą su `order_id` ir kitais reikalingais laukais
        $review = Review::create([
            'user_id' => User::factory()->create()->id,
            'order_id' => $order->id, // Pridedame order_id
            'trainer_profile_id' => \App\Models\TrainerProfile::factory()->create()->id, // Pridedame trainer_profile_id
            'rating' => 2, // Pavyzdžiui, blogas įvertinimas
            'comment' => 'Tai yra blogas atsiliepimas.'
        ]);

        // Patikriname, ar atsiliepimas buvo sukurtas su reikiama order_id
        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id, // Patikriname, kad order_id yra teisingas
            'rating' => 2, // Patikriname, kad rating yra teisingas
            'comment' => 'Tai yra blogas atsiliepimas.'
        ]);

        // Galite pridėti papildomus patikrinimus, pvz., filtrus
        $response = $this->get(route('admin.reviews.bad')); // Pvz., filtravimas pagal blogus atsiliepimus

        $response->assertStatus(200);
    }

    // Testas atsiliepimo ištrynimui
    /** @test */
    public function it_deletes_a_review()
    {
        // Sukuriame užsakymą
        $order = Order::factory()->create();

        // Sukuriame trenerio profilį
        $trainerProfile = \App\Models\TrainerProfile::factory()->create();

        // Sukuriame atsiliepimą su `order_id`, `trainer_profile_id` ir `rating`
        $review = Review::create([
            'user_id' => User::factory()->create()->id,
            'order_id' => $order->id, // Pridedame order_id
            'trainer_profile_id' => $trainerProfile->id, // Pridedame trainer_profile_id
            'rating' => 5, // Pridedame rating
            'comment' => 'Blogas atsiliepimas'
        ]);

        // Prisijungiame kaip admin
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Atliekame užklausą, kad ištrintume atsiliepimą
        $response = $this->delete(route('admin.reviews.destroy', $review->id));

        // Patikriname, kad atsiliepimas buvo sėkmingai ištrintas
        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    // Testas pridedant netinkamą žodį į bad_words lentelę
    /** @test */
    public function it_stores_a_bad_word()
    {
        // Sukuriame admin vartotoją
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Atliekame užklausą, kad pridėtume netinkamą žodį
        $response = $this->post(route('admin.badwords.store'), [
            'word' => 'naujasZodis'
        ]);

        // Patikriname, ar žodis buvo sėkmingai pridėtas
        $response->assertRedirect();
        $this->assertDatabaseHas('bad_words', ['word' => 'naujasZodis']);
    }
}
