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

        $response->assertViewIs('addFriend');
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

        $response->assertViewIs('addFriend')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 1 &&
                            $data['size'] === 10 &&
                            $data['users']->count() === 10 &&
                            $data['has_more_pages'] === true &&
                            $data['last_page'] === 2 &&
                            $data['on_first_page'] === true;
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

        $response->assertViewIs('addFriend')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 2 &&
                            $data['size'] === 10 &&
                            $data['users']->count() === 5 &&
                            $data['has_more_pages'] === false &&
                            $data['last_page'] === 2 &&
                            $data['on_first_page'] === false;
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

        $response->assertViewIs('addFriend')
                ->assertViewHas('data', function ($data) {
                    return $data['page'] === 1 &&
                            $data['size'] === 10 &&
                            $data['on_first_page'] === true &&
                            $data['users']->contains('username', 'budipratama');
                });
    }
}