<?php

namespace Database\Factories;

use App\Models\FriendRequests;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;

/**
 * @extends Factory<FriendRequests>
 */
class FriendRequestsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::factory()->create();

        return [
            'id_pengirim' => $sender->id,
            'id_penerima' => User::factory()->create()->id,
            'status' => 'pending',
        ];
    }
}
