<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\BadWord;

class BadWordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_bad_word()
    {
        $badWord = BadWord::create(['word' => 'testword']);

        $this->assertDatabaseHas('bad_words', [
            'word' => 'testword',
        ]);

        $this->assertEquals('testword', $badWord->word);
    }
}
