# ⚡ Quikk Framework v0.2
Develop faster, think quikk ⚡

Versi ringan, cepat, dan modular dari framework PHP native berbasis OOP. Dirancang untuk pengembangan aplikasi web yang **aman**, **terstruktur**, dan **mudah diperluas**.

### 📦 Spesifikasi
- **Versi:** 0.2
- **Bahasa:** PHP 8.x, MySQL 8.x
- **Struktur:** Native OOP Modular
- **Lisensi:** MIT (opsional)

---

## 📁 Struktur Direktori
quikk/
├── app/
│ ├── Config/ → config.php (global config)
│ ├── Database/ → schema.sql, seeder, DB.php, SeederSentinel.php
│ └── Services/ → AuthService.php, MenuManager.php, Router.php, ModuleRegistrar.php
├── pages/ → Halaman aplikasi
├── public/ → Aset statis (JS/CSS)
├── logs/ → Log aktivitas (opsional)
├── README.md → Dokumentasi ini

---

## 🚀 Cara Memulai

1. Clone repository ini:
   ```bash
   git clone https://github.com/username/quikk.git
   
2. Import schema.sql ke database Anda.

3, Jalankan seeder SQL untuk user, menu, dan hak akses:
    seed_users.sql
    seed_menus.sql
    seed_access.sql

4. Konfigurasikan file app/Config/config.php sesuai dengan lingkungan Anda.
5. 🔐 Fitur Keamanan
    Password hash menggunakan password_hash()
    CSRF Token + Rate Limiter
    Logging login (ke database / file)
    Validasi input dan session regenerasi
    Routing berbasis token terenkripsi

🧩 Class Utama
Class	Fungsi
DB	Singleton koneksi PDO
AuthService	Login, logout, CSRF, session, logging
MenuManager	Load menu dinamis sesuai hak akses
Router	Resolusi halaman berdasarkan token URL
ModuleRegistrar	Registrasi modul: SQL + menu + hak akses otomatis
SeederSentinel	Mencegah seed SQL dijalankan lebih dari 1 kali

📂 Contoh Konfigurasi (config.php)
return [
  'db_host' => 'localhost',
  'db_name' => 'quikk',
  'db_user' => 'root',
  'db_pass' => '',
  'base_url' => 'http://localhost/quikk/',
  'rate_limit_attempts' => 5,
  'enable_csrf' => true,
  'token_length' => 10,
  'pages_path' => __DIR__ . '/../../pages/',
  'fallback_404' => 'pages/error/404.php'
];

👤 Testing Default Login
Role	Username	Password
Admin	admin	admin
User	user	user

📦 Cara Menambahkan Modul Baru
    Tambahkan file .php ke folder /pages/
    Tambahkan SQL schema modul ke /app/Database/modules/nama_modul.sql
    Jalankan kode berikut:
use App\Database\DB;
use App\Services\ModuleRegistrar;

$db = DB::getConnection();
$config = include '../app/Config/config.php';

$registrar = new ModuleRegistrar($db, 'nama_modul', $config);
$registrar->register();

📌 Catatan Tambahan
    Tidak perlu modifikasi core class untuk menambah fitur
    Sistem mendukung struktur multi-level menu
    Mudah untuk integrasi dengan Bootstrap / JS
    Cocok untuk aplikasi internal, panel admin, dan backend API ringan

📄 Dokumentasi Lain
    schema.sql – Struktur database utama
    SRS_Quikk_v0.2.docx – Spesifikasi kebutuhan sistem
    Dokumentasi_Lengkap_Quikk_v0.2.docx
    
    

    

