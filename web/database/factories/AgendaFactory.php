<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agenda>
 */
class AgendaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agenda_name = fake()->randomElement([
                            'Mathematics Study Group',
                            'Physics Discussion',
                            'Database Design Review',
                            'Laravel Workshop',
                            'Operating Systems Revision',
                            'Machine Learning Practice',
                            'UI/UX Design Session',
                            'Weekly Programming Meetup',
                            'Final Exam Preparation',
                            'Algorithm Study Session',
                        ]);
        $agenda_owner = User::factory()->create();
        $address = fake()->address();
        $random_additional_days = random_int(1, 30);
        $random_start_hours = random_int(8, 18);
        $random_add_hours = random_int(0, 20 - $random_start_hours);
        $start_time = now()->addWeek()->addDays($random_additional_days)->addHours($random_start_hours);
        $end_time = $start_time->addHours($random_add_hours);

        return [
            'nama_agenda' => $agenda_name,
            'id_penyelenggara' => $agenda_owner->id,
            'lokasi' => $address,
            'waktu_mulai' => $start_time,
            'waktu_berakhir' => $end_time,
            'status' => 'belum dimulai'
        ];
    }
}
