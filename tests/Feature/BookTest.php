<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class BookTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAllBook() {
        $user1 = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books');
        $response1->assertSee('book_id');
    }
    public function testDetailExistingBook() {
        $user1 = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/c901d508e00e4d36895b98827709897d');
        $response1->assertSee('book_id');
    }
    public function testDetailNonExistingBook() {
        $user1 = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/c901d5ade00e4d36895b98827709897d');
        $response1->assertDontSee('book_id');
    }
    public function testCreateDeleteBook() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/books',[
            'title' => 'Book Title'
        ]);
        $response1->assertSee('book_id');
        $book = json_decode($response1->getContent());
        // echo($user);
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('DELETE', '/books/'.$book->book_id);
        $response2->assertSee('Deleted');
    }
    public function testEditBook() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('PUT', '/books',[
            'book_id' => 'b3e19d5d67de4951987e6c6875ef8b4d',
            'title' => 'BookTitle'
        ]);
        $response1->assertSee('book_id');
    }
    public function testAddCategories() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/books/categories',[
            'book_id' => 'b3e19d5d67de4951987e6c6875ef8b4d',
            'category_id' => 'dffe8cbe0aa949c39f65508f255feadd'
        ]);
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/b3e19d5d67de4951987e6c6875ef8b4d');
        $response2->assertSee('Fantasy');
    }
}
