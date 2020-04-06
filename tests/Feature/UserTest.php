<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $response = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response->getContent();
        $response->assertOk();
        $this->assertAuthenticated();
        $response = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        $this->assertAuthenticated();
    }

    public function testLoginFail() {
        $this->assertGuest();
        $response = $this->json('POST', '/', ['username' => '', 'password' => '']);
        $this->assertGuest();
    }

    public function testAllUserAdmin() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/users');
        $response1->assertSee('user_id');
    }
    public function testAllUserUser() {
        $user2 = $this->json('POST', '/', ['username' => 'user', 'password' => '1234']);
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user2->getContent(),
        ])->json('GET', '/users');
        $response2->assertSee('code');
    }
    public function testUserDetailExisting() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/users/7efe0929fc2d4774a5ba50e95002eab9');
        $response1->assertSee('user_id');
    }
    public function testUserDetailNonExisting() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('GET', '/users/7efe0929fc2d4774a5ba50e950aasdb9');
        $response1->assertSee('No query');
    }

    public function testCreateDeleteUser() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('POST', '/users',[
            'username' => 'newUser',
            'password' => '1234',
            'is_admin' => 0
        ]);
        $response1->assertSee('user_id');
        $user = json_decode($response1->getContent());
        // echo($user);
        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('DELETE', '/users/'.$user->user_id);
        $response2->assertSee('Deleted');
    }
    public function testEditUser() {
        $user1 = $this->json('POST', '/', ['username' => 'admin', 'password' => '1234']);
        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer '.$user1->getContent(),
        ])->json('PUT', '/users',[
            'user_id' => '7efe0929fc2d4774a5ba50e95002eab9',
            'username' => 'user1',
            'password' => '1234',
            'is_admin' => 0
        ]);
        $response1->assertSee('user_id');
    }
}
