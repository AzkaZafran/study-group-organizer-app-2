# Study Group Organizer App

Aplikasi berbasis digital yang dirancang sebagai solusi terpadu untuk mengelola kegiatan belajar kelompok secara lebih efektif dan terstruktur. Aplikasi ini membantu mahasiswa dan pelajar dalam merencanakan serta mengatur aktivitas belajar bersama tanpa harus menggunakan banyak platform yang berbeda. Dengan sistem yang terintegrasi, seluruh kebutuhan belajar kelompok dapat dilakukan dalam satu tempat, sehingga proses koordinasi menjadi lebih mudah dan efisien.

---

# 📌 Overview

## Features

- Autentikasi User
- Kelola Agenda
- Kelola Teman Belajar
- Dashboard Analitik Agenda
- Kelola Catatan Agenda

---

# 🛠 Tech Stack

## Backend
- Laravel
- MySQL

## Frontend
- Flutter
- Bootstrap

## Tools
- Git
- Android Studio
- VS Code

---

# 📂 Project Structure

```text
project-root/
├── web/            # Laravel project (Web Frontend + Backend + API)
├── mobile/         # Flutter project (Mobile Frontend)
└── README.md
```

---

# 📋 Requirements

## General
- Git
- Internet connection

## Web Requirements (+ backend)
- PHP >= 8.2
  - Install disini: [https://www.php.net/downloads.php](https://www.php.net/downloads.php) (Note: Install Non Thread Safe version)
  - Buka folder PHP yang sudah terinstalasi tadi
  - Hapus file `php.ini` dan ganti dengan file `php.ini.example` dalam git repo ini lalu ganti nama file menjadi `php.ini`
- Composer
  - Install disini: [https://getcomposer.org/download/](https://getcomposer.org/download/)
  - Video panduan instalasi: [https://youtu.be/nus8eLPNZF8?si=GOCn7mvNWJTvh7y2](https://youtu.be/nus8eLPNZF8?si=GOCn7mvNWJTvh7y2)
- MySQL
  - Download installer (Windows) disini: [https://dev.mysql.com/downloads/installer/](https://dev.mysql.com/downloads/installer/)
  - Video panduan instalasi (Windows) (Mysql Server + Workbench + Shell): [https://youtu.be/hiS_mWZmmI0?si=JwJmbp4-FgSzZhha](https://youtu.be/hiS_mWZmmI0?si=JwJmbp4-FgSzZhha)
- Node.js
  - Download installer disini: [https://nodejs.org/en/download](https://nodejs.org/en/download)
  - Video panduan instalasi (windows) (durasi panduan: 0:00 - 5:09): [https://youtu.be/Iqj8bUJweiU?si=lblXgG1rq7BuJ1Ck](https://youtu.be/Iqj8bUJweiU?si=lblXgG1rq7BuJ1Ck)

## Mobile Requirements
- Android SDK (Note: Jika sudah pernah setup Android Studio sampai membuat projek, maka paket ini sudah terinstal, jika belum, bisa lihat panduannya disini: [https://youtu.be/xBTECTbYDwc?si=wp3FYln_8LiUkWHc](https://youtu.be/xBTECTbYDwc?si=wp3FYln_8LiUkWHc))
- Flutter SDK
  - Instalasi flutter secara manual: [https://docs.flutter.dev/install/manual](https://docs.flutter.dev/install/manual)
  - Video panduan setup flutter di Android Studio: [https://youtu.be/jsvqxhmu9-E?si=60_hb9QqNc0iz0UC](https://youtu.be/jsvqxhmu9-E?si=60_hb9QqNc0iz0UC)
- Emulator or Android device

---

# 🚀 Backend Setup (Hybrid Monolith Laravel)

## 1. Clone Repository

```bash
git clone https://github.com/AzkaZafran/study-group-organizer-app-2.git
```

---

## 2. Masuk Folder Web

```bash
cd web
```

---

## 3. Install Dependencies

```bash
composer install
npm install
```

---

## 4. Buat File Environment 

```bash
cp .env.example .env
```

---

## 5. Buat Kunci Acak Aplikasi

```bash
php artisan key:generate
```

---

## 6. Konfigurasi Database

Edit `.env`

```env
DB_DATABASE=nama_database_kamu
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan nama database, username, dan password dengan yang dipakai pada DBMS MySQL masing - masing. Jika tidak memakai password bisa dikosongi saja.

---

## 7. Konfigurasi Mailer

Edit `.env`

```env
MAIL_USERNAME=alamat_gmail_kamu
MAIL_PASSWORD=akun_google_app_password
```

Untuk membuat `app password`, ikuti langkah - langkah berikut:
1. Sebelum membuat `app password`, pastikan kamu sudah atur "verifikasi 2 langkah" pada akun google yang dipakai.
2. Buka Browser dan pastikan sudah login menggunakan akun google yang akan dipakai, lalu buka URL [https://myaccount.google.com/](https://myaccount.google.com/).
3. Cari dengan kata kunci "Sandi aplikasi" atau "App passwords" lalu klik hasil sugesti yang sesuai dengan kata kunci.
4. Masukkan password kalian jika ditanyakan lalu isi nama aplikasi untuk membuat password khusus.
5. Klik "Create" dan google akan memunculkan `app password` yang dibuat

---

## 8. Run Migration

```bash
php artisan migrate
```

---

## 9. Run Laravel Server

```bash
php artisan serve
npm run dev
```

Backend akan jalan di URL berikut:

```text
http://127.0.0.1:8000
```

---

# 🧪 Running Tests

## Laravel Tests

Buka folder `web` dan jalankan:

```bash
php artisan test
```

---

# Buat Data Dummy

```bash
php artisan db:seed --class=DatabaseSeeder
```

---

# 🖼 Web UI Preview

## Login Page

![Login Page](<web/docs/web ui preview/login_page.png>)

---

## Dashboard

![Dashboard](<web/docs/web ui preview/dashboard_page.png>)

---

## Friends

![Friends_page](<web/docs/web ui preview/user_friends_page.png>)

---

# 🔌 API Documentation

Example endpoint:

```http
GET /api/agendas
```

Example response:

```json
{
  "success": true,
  "data": []
}
```

---

# 👥 Contributors

- Azka Zafran Andiani
- Teguh Ryan Firmansyah
- Ahmad Pasha Maurinho
- Nabila Amilatul Jannah
- Ajax Amstermarstama Januariel

---