<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\Partisipan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partisipan>
 */
class PartisipanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $new_agenda = Agenda::factory()->create();
        $user = User::factory()->create();

        return [
            'id_agenda' => $new_agenda->id_agenda,
            'id_user' => $user->id,
            'status' => 'pending'
        ];
    }
}
