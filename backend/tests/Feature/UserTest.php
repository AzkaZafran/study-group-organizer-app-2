<?php

namespace Tests\Feature;

use Tests\TestCase;

class UserTest extends TestCase {
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

    }

    public function testRegisterUsernameAlreadyExist() {

    }

    public function testRegisterEmailAlreadyExist() {

    }
}
