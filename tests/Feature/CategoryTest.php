<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateDeleteCategory() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $name = Str::random(4);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/categories',[
            'name' => $name
        ]);
        $response1->assertSee($name);
        $category = json_decode($response1->getContent());
        // echo($user);
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('DELETE', '/categories/'.$category->category_id);
        $response2->assertSee('Deleted');
    }

    public function testGetAllCategory() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/categories');
        $response1->assertSee('category_id');
    }

    public function testEditCategory() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $name = Str::random(5);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('PUT', '/categories', [
            'category_id'=> '91dbce94cd6f41a1883e19480e6609a8',
            'name'=> $name,
        ]);
        $response1->assertSee($name);
    }
}
