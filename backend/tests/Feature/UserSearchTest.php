<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserSearchTest extends TestCase {
    public function testViewSearchPageSuccess() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $this->actingAs($auth_user);

        $response = $this->get('/friend/search');

        $response->assertViewIs('test');
    }

    public function testViewSearchPageFailed() {
        $response = $this->get('/friend/search');

        $response->assertRedirect('/login');
    }

    public function testSearchPageWithQuery() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $response = $this->get(route('search new friend', ['username' => '']));

        $response->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 1 &&
                            $data['size'] === 10 &&
                            $data['users']->count() === 10;
                });
    }

    public function testSearchPageWithQueryAndPage() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $response = $this->get(route('search new friend', [
            'username' => '',
            'page' => 2
        ]));

        $response->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 2 &&
                            $data['size'] === 10 &&
                            $data['users']->count() === 5;
                });
    }

    public function testSearchPageWithUsername() {
        $data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $auth_user = User::create($data);

        $data = [
            'username' => 'budipratama',
            'email' => 'budipratama@gmail.com',
            'password' => Hash::make('testestestest'),
            'is_verified' => true
        ];

        $user = User::create($data);

        User::factory()->count(15)->create();

        $this->actingAs($auth_user);

        $response = $this->get(route('search new friend', [
            'username' => 'budi'
        ]));

        $response->assertViewIs('test')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 1 &&
                            $data['size'] === 10 &&
                            $data['users']->contains('username', 'budipratama');
                });
    }
}