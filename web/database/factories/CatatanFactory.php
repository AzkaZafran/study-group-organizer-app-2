<?php

namespace Database\Factories;

use App\Models\Agenda;
use App\Models\Catatan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Catatan>
 */
class CatatanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $agenda = Agenda::factory()->create();
        $author = User::factory()->create();

        $catatan_pack = [
            [
                'judul' => 'Pengenalan HTML',
                'isi' => 'Mempelajari struktur dasar HTML seperti <!DOCTYPE>, html, head, dan body. Memahami fungsi heading, paragraph, gambar, dan hyperlink.'
            ],
            [
                'judul' => 'Semantic HTML',
                'isi' => 'Menggunakan elemen semantic seperti header, nav, main, section, article, dan footer agar struktur halaman lebih mudah dipahami.'
            ],
            [
                'judul' => 'Dasar CSS',
                'isi' => 'Mempelajari selector, property, dan value pada CSS untuk mengatur tampilan halaman seperti warna, font, margin, dan padding.'
            ],
            [
                'judul' => 'Flexbox Layout',
                'isi' => 'Memahami penggunaan display flex, justify-content, align-items, flex-direction, dan flex-grow untuk membuat layout yang responsif.'
            ],
            [
                'judul' => 'CSS Grid',
                'isi' => 'Mempelajari CSS Grid untuk membuat layout dua dimensi menggunakan grid-template-columns, grid-template-rows, dan gap.'
            ],
            [
                'judul' => 'Responsive Design',
                'isi' => 'Menggunakan media query dan Bootstrap agar tampilan website dapat menyesuaikan berbagai ukuran layar.'
            ],
            [
                'judul' => 'Bootstrap Components',
                'isi' => 'Mempelajari penggunaan komponen Bootstrap seperti navbar, card, modal, alert, form, dan button.'
            ],
            [
                'judul' => 'Dasar JavaScript',
                'isi' => 'Memahami variabel, tipe data, operator, percabangan, fungsi, dan perulangan menggunakan JavaScript.'
            ],
            [
                'judul' => 'DOM Manipulation',
                'isi' => 'Mengubah isi halaman menggunakan JavaScript dengan querySelector, addEventListener, dan manipulasi elemen DOM.'
            ],
            [
                'judul' => 'Fetch API',
                'isi' => 'Mengambil data dari server menggunakan Fetch API dan menampilkan hasilnya ke halaman web.'
            ],
            [
                'judul' => 'Pengenalan Laravel',
                'isi' => 'Mengenal struktur project Laravel, routing, controller, model, migration, dan Blade template.'
            ],
            [
                'judul' => 'Routing Laravel',
                'isi' => 'Membuat route GET, POST, PUT, DELETE serta memahami route parameter dan route name.'
            ],
            [
                'judul' => 'Blade Template',
                'isi' => 'Menggunakan extends, section, yield, include, dan component Blade agar tampilan lebih modular.'
            ],
            [
                'judul' => 'Migration Database',
                'isi' => 'Membuat migration untuk membuat tabel, menambah kolom, mengubah kolom, dan mengelola struktur database.'
            ],
            [
                'judul' => 'Eloquent ORM',
                'isi' => 'Melakukan operasi CRUD menggunakan model Eloquent tanpa menulis query SQL secara langsung.'
            ],
            [
                'judul' => 'Relationship Eloquent',
                'isi' => 'Mempelajari relasi hasOne, hasMany, belongsTo, dan belongsToMany beserta eager loading.'
            ],
            [
                'judul' => 'Form Validation',
                'isi' => 'Melakukan validasi data menggunakan Laravel Validator dan Form Request serta menampilkan pesan kesalahan kepada pengguna.'
            ],
            [
                'judul' => 'Authentication',
                'isi' => 'Mengimplementasikan login, register, logout, middleware auth, dan proteksi halaman.'
            ],
            [
                'judul' => 'Laravel Livewire',
                'isi' => 'Membuat komponen interaktif menggunakan Livewire sehingga halaman dapat diperbarui tanpa menulis JavaScript secara manual.'
            ],
            [
                'judul' => 'File Upload',
                'isi' => 'Mengunggah file menggunakan Laravel Storage serta melakukan validasi ukuran dan tipe file.'
            ],
            [
                'judul' => 'REST API',
                'isi' => 'Membuat endpoint API untuk operasi CRUD dan mengembalikan response dalam format JSON.'
            ],
            [
                'judul' => 'Git Workflow',
                'isi' => 'Menggunakan Git untuk commit, branch, merge, pull request, dan menyelesaikan merge conflict.'
            ],
            [
                'judul' => 'Debugging Laravel',
                'isi' => 'Menggunakan dd(), dump(), log Laravel, dan browser DevTools untuk mencari penyebab bug.'
            ],
            [
                'judul' => 'Optimasi Database',
                'isi' => 'Menggunakan eager loading, indexing, dan optimasi query agar performa aplikasi menjadi lebih baik.'
            ],
            [
                'judul' => 'Laravel Policies',
                'isi' => 'Mengatur hak akses pengguna menggunakan Policy sehingga hanya pengguna tertentu yang dapat mengubah atau menghapus data.'
            ],
            [
                'judul' => 'Unit Testing',
                'isi' => 'Membuat Unit Test dan Feature Test menggunakan PHPUnit atau Pest untuk memastikan fitur berjalan sesuai harapan.'
            ],
            [
                'judul' => 'Deployment Aplikasi',
                'isi' => 'Mempelajari proses deployment aplikasi Laravel ke server serta konfigurasi environment production.'
            ],
            [
                'judul' => 'Keamanan Web',
                'isi' => 'Memahami CSRF Protection, XSS Prevention, SQL Injection, validasi input, dan penggunaan middleware keamanan.'
            ],
            [
                'judul' => 'Arsitektur Project',
                'isi' => 'Menata struktur project menggunakan Service Layer, Repository, serta prinsip SOLID agar kode mudah dipelihara.'
            ],
            [
                'judul' => 'Evaluasi Pembelajaran',
                'isi' => 'Melakukan review terhadap materi Web Development yang telah dipelajari, mencatat kendala, dan menyusun target pembelajaran berikutnya.'
            ],
        ];

        $random_catatan_idx = random_int(1, 30) - 1;

        return [
            'id_author' => $author->id,
            'id_agenda' => $agenda->id_agenda,
            'judul_catatan' => $catatan_pack[$random_catatan_idx]['judul'],
            'catatan' => $catatan_pack[$random_catatan_idx]['isi']
        ];
    }
}
