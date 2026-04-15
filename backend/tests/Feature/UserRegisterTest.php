<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserRegisterTest extends TestCase {
    public function testRegisterSuccess() {
        $response = $this->post('/register', [
            'username' => 'azkazafran78',
            'email' => "azkazafran78@gmail.com",
            "password" => "test"
        ]);

        $response->assertStatus(200)->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['username'] === 'azkazafran78' &&
                            $data['email'] === 'azkazafran78@gmail.com';
                });
    }

    public function testRegisterFailed() {
        $response = $this->from('/test')
                        ->post('register', [
                            'username' => '',
                            'email' => '',
                            'password' => ''
                        ]);
        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['username', 'email', 'password']);
    }

    public function testRegisterUsernameAlreadyExist() {
        $this->testRegisterSuccess();

        $response = $this->from('/test')
                        ->post('/register', [
                            'username' => 'azkazafran78',
                            'email' => "azkazafran79@gmail.com",
                            "password" => "test"
                        ]);
        
        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['message']);
    }

    public function testRegisterEmailAlreadyExist() {
        $this->testRegisterSuccess();

        $response = $this->from('/test')
                        ->post('/register', [
                            'username' => 'azkazafran79',
                            'email' => "azkazafran78@gmail.com",
                            "password" => "test"
                        ]);

        $response->assertRedirect('/test')
                ->assertSessionHasErrors(['message']);
    }
}
