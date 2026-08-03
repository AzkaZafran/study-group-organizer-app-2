<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\CatatanTerbaca;
use App\Models\FriendRequests;
use App\Models\Partisipan;
use App\Models\UndanganAgenda;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private function generate_registered_user_dummy_data() {
        $user_data = [
            'username' => 'ryan456',
            'email' => 'ryan456@gmail.com',
            'password' => Hash::make('passpelajarryan123_'),
            'is_verified' => true
        ];

        $user = User::updateOrCreate(
            [
                'username' => $user_data['username'],
                'email' => $user_data['email']
            ],
            $user_data
        );

        $user_mutual_friends = User::factory()->count(10)->create();

        foreach ($user_mutual_friends as $friend) {
            FriendRequests::firstOrCreate(
                [
                    'id_pengirim' => $user->id,
                    'id_penerima' => $friend->id,
                ],
                [
                    'status' => 'mutual',
                ]
            );

            FriendRequests::firstOrCreate(
                [
                    'id_pengirim' => $friend->id,
                    'id_penerima' => $user->id,
                ],
                [
                    'status' => 'mutual',
                ]
            );
        }

        $other_user_send_friend_request = User::factory()->count(3)->create();

        foreach ($other_user_send_friend_request as $user_send_friend_request) {
            FriendRequests::firstOrCreate(
                [
                    'id_pengirim' => $user_send_friend_request->id,
                    'id_penerima' => $user->id,
                ],
                [
                    'status' => 'pending',
                ]
            );
        }

        $agenda_organizer = $user_mutual_friends->first();

        $agenda_data = Agenda::factory()->make([
            'id_penyelenggara' => $agenda_organizer->id,
            'waktu_mulai' => now()->addDays(3),
            'waktu_berakhir' => now()->addDays(3)->addHours(3)
                                    ->min(now()->addDays(3)->endOfDay()),
            'status' => 'belum dimulai'
        ]);

        $agenda = Agenda::create($agenda_data->getAttributes());

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $agenda_organizer->id
            ],
            [
                'status' => 'ikut'
            ]
        );

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $agenda->id_agenda,
                'id_user' => $user->id
            ],
            [
                'status' => 'pending'
            ]
        );

        $agenda_invite_code_data = UndanganAgenda::firstOrCreate(
            [
                'id_agenda' => $agenda->id_agenda,
                'invite_code' => 'AZX07NOL'
            ],
            [
                'expired_at' => now()->addDay()
            ]
        );

        return $user;
    }

    private function generate_agenda_participant_dummy_data() {
        $user_data = [
            'username' => 'pasha99',
            'email' => 'pasha99@gmail.com',
            'password' => Hash::make('pashapartisipan_'),
            'is_verified' => true
        ];

        $user = User::updateOrCreate(
            [
                'username' => $user_data['username'],
                'email' => $user_data['email']
            ],
            $user_data
        );

        return $user;
    }

    private function generate_agenda_organizer_and_participant_dummy_data() {
        $user_data = [
            'username' => 'azkazafran78',
            'email' => 'azkazafran78@gmail.com',
            'password' => Hash::make('password123456789'),
            'is_verified' => true
        ];

        $user = User::updateOrCreate(
            [
                'username' => $user_data['username'],
                'email' => $user_data['email']
            ],
            $user_data
        );

        $user_mutual_friends = User::factory()->count(10)->create();

        foreach ($user_mutual_friends as $friend) {
            FriendRequests::firstOrCreate(
                [
                    'id_pengirim' => $user->id,
                    'id_penerima' => $friend->id,
                ],
                [
                    'status' => 'mutual',
                ]
            );

            FriendRequests::firstOrCreate(
                [
                    'id_pengirim' => $friend->id,
                    'id_penerima' => $user->id,
                ],
                [
                    'status' => 'mutual',
                ]
            );
        }

        $pending_agendas_data = Agenda::factory()->count(5)->make([
            'id_penyelenggara' => $user->id,
            'waktu_mulai' => now()->addDays(3),
            'waktu_berakhir' => now()->addDays(3)->addHours(3)
                                    ->min(now()->addDays(3)->endOfDay()),
            'status' => 'belum dimulai'
        ]);

        $pending_agendas = collect();

        foreach ($pending_agendas_data as $agenda_data) {
            $agenda = Agenda::create($agenda_data->getAttributes());

            $pending_agendas->push($agenda);

            Partisipan::firstOrCreate(
                [
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $user->id,
                ],
                [
                    'status' => 'ikut'
                ]
            );

            $agenda_participants = $user_mutual_friends->random(5);

            foreach ($agenda_participants as $participant) {
                Partisipan::firstOrCreate(
                    [
                        'id_agenda' => $agenda->id_agenda,
                        'id_user' => $participant->id,
                    ],
                    [
                        'status' => 'ikut'
                    ]
                );
            }
        }

        $running_agenda_data = Agenda::factory()->make([
            'id_penyelenggara' => $user->id,
            'waktu_mulai' => now()->subHour()
                                ->max(now()->startOfDay()),
            'waktu_berakhir' => now()->addHour()
                                    ->min(now()->endOfDay()),
            'status' => 'sedang berjalan'
        ]);

        $running_agenda = Agenda::create($running_agenda_data->getAttributes());

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $running_agenda->id_agenda,
                'id_user' => $user->id,
            ],
            [
                'status' => 'ikut'
            ]
        );

        $agenda_participants = $user_mutual_friends->random(5);

        foreach ($agenda_participants as $participant) {
            Partisipan::firstOrCreate(
                [
                    'id_agenda' => $running_agenda->id_agenda,
                    'id_user' => $participant->id,
                ],
                [
                    'status' => 'ikut'
                ]
            );

            $catatan_data = Catatan::factory()->make([
                'id_author' => $participant->id,
                'id_agenda' => $running_agenda->id_agenda
            ])->toArray();

            $catatan = Catatan::create($catatan_data);

            CatatanTerbaca::create([
                'id_catatan' => $catatan->id_catatan,
                'id_user' => $participant->id,
                'status' => 'sudah dibaca'
            ]);
        }

        $finished_agendas_data = Agenda::factory()->count(5)->make([
            'id_penyelenggara' => $user->id,
            'waktu_mulai' => now()->subDays(2),
            'waktu_berakhir' => now()->subDays(2)->addHours(3)
                                    ->min(now()->subDays(2)->endOfDay()),
            'status' => 'selesai'
        ]);

        $finished_agendas = collect();

        foreach ($finished_agendas_data as $agenda_data) {
            $agenda = Agenda::create($agenda_data->getAttributes());

            $finished_agendas->push($agenda);
            
            Partisipan::firstOrCreate(
                [
                    'id_agenda' => $agenda->id_agenda,
                    'id_user' => $user->id,
                ],
                [
                    'status' => 'ikut'
                ]
            );

            $agenda_participants = $user_mutual_friends->random(5);

            foreach ($agenda_participants as $participant) {
                Partisipan::firstOrCreate(
                    [
                        'id_agenda' => $agenda->id_agenda,
                        'id_user' => $participant->id,
                    ],
                    [
                        'status' => 'ikut'
                    ]
                );

                $catatan_data = Catatan::factory()->make([
                    'id_author' => $participant->id,
                    'id_agenda' => $agenda->id_agenda
                ])->toArray();

                $catatan = Catatan::create($catatan_data);

                CatatanTerbaca::create([
                    'id_catatan' => $catatan->id_catatan,
                    'id_user' => $participant->id,
                    'status' => 'sudah dibaca'
                ]);
            }
        }

        $agenda_participant = $this->generate_agenda_participant_dummy_data();

        FriendRequests::firstOrCreate(
            [
                'id_pengirim' => $user->id,
                'id_penerima' => $agenda_participant->id,
            ],
            [
                'status' => 'mutual',
            ]
        );

        FriendRequests::firstOrCreate(
            [
                'id_pengirim' => $agenda_participant->id,
                'id_penerima' => $user->id,
            ],
            [
                'status' => 'mutual',
            ]
        );

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $pending_agendas->first()->id_agenda,
                'id_user' => $agenda_participant->id,
            ],
            [
                'status' => 'ikut'
            ]
        );

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $running_agenda->id_agenda,
                'id_user' => $agenda_participant->id,
            ],
            [
                'status' => 'ikut'
            ]
        );

        Partisipan::firstOrCreate(
            [
                'id_agenda' => $finished_agendas->first()->id_agenda,
                'id_user' => $agenda_participant->id,
            ],
            [
                'status' => 'ikut'
            ]
        );
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->generate_registered_user_dummy_data();

        $this->generate_agenda_organizer_and_participant_dummy_data();

        $strangers = User::factory()->count(10)->create();
    }
}
