<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CommentTest extends TestCase
{
    
    public function testAddApproveComment() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $comment = Str::random(10);
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/comments',[
            'book_id' => 'b3e19d5d67de4951987e6c6875ef8b4d',
            'comment' => $comment
        ]);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/b3e19d5d67de4951987e6c6875ef8b4d');
        $response1->assertDontSee($comment);

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/comments');
        $response2->assertSee($comment);
        $comment_id = json_decode($response->getContent())->comment_id;
        $response3 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/comments/approve', [
            'comment_id' => $comment_id,
        ]);
        $response4 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/books/b3e19d5d67de4951987e6c6875ef8b4d');
        $response4->assertSee($comment);
    }
}
