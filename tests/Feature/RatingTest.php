<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddRating() {
        $user1 = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/b3e19d5d67de4951987e6c6875ef8b4d');
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/ratings',[
            'book_id' => 'b3e19d5d67de4951987e6c6875ef8b4d',
            'rating' => 5
        ]);
    }
}
