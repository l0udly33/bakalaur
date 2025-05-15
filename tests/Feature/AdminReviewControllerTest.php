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

    
    /** @test */
    public function it_filters_bad_reviews()
    {
        
        $order = Order::factory()->create();

        
        $review = Review::create([
            'user_id' => User::factory()->create()->id,
            'order_id' => $order->id, 
            'trainer_profile_id' => \App\Models\TrainerProfile::factory()->create()->id, 
            'rating' => 2, 
            'comment' => 'Tai yra blogas atsiliepimas.'
        ]);

        
        $this->assertDatabaseHas('reviews', [
            'order_id' => $order->id, 
            'rating' => 2, 
            'comment' => 'Tai yra blogas atsiliepimas.'
        ]);

        
        $response = $this->get(route('admin.reviews.bad')); 

        $response->assertStatus(200);
    }

    
    /** @test */
    public function it_deletes_a_review()
    {
        
        $order = Order::factory()->create();

        
        $trainerProfile = \App\Models\TrainerProfile::factory()->create();

        
        $review = Review::create([
            'user_id' => User::factory()->create()->id,
            'order_id' => $order->id, 
            'trainer_profile_id' => $trainerProfile->id, 
            'rating' => 5, 
            'comment' => 'Blogas atsiliepimas'
        ]);

        
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        
        $response = $this->delete(route('admin.reviews.destroy', $review->id));

        
        $response->assertRedirect();
        $this->assertDatabaseMissing('reviews', ['id' => $review->id]);
    }

    
    /** @test */
    public function it_stores_a_bad_word()
    {
        
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        
        $response = $this->post(route('admin.badwords.store'), [
            'word' => 'naujasZodis'
        ]);

        
        $response->assertRedirect();
        $this->assertDatabaseHas('bad_words', ['word' => 'naujasZodis']);
    }
}
