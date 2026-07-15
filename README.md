# Smart Event Campus

Sistem Informasi Pengelolaan Kegiatan Kampus (Seminar, Workshop, Lomba, dan Pelatihan) menggunakan PHP dan MySQL.

## Fitur Utama
1. **Halaman Publik**: Pengunjung dapat melihat daftar kegiatan kampus yang akan datang secara real-time.
2. **Dashboard Admin**: Mengelola seluruh data kegiatan (Create, Read, Update, Delete).
3. **Sistem Login**: Admin area diamankan menggunakan sistem otentikasi.

## Teknologi yang Digunakan
- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System tanpa framework)
- **Backend**: PHP (Native)
- **Database**: MySQL/MariaDB
- **Ikon**: FontAwesome 6

## Instalasi
1. Clone atau unduh repositori ini ke dalam direktori `htdocs` (jika menggunakan XAMPP) atau direktori root server lokal Anda.
   ```bash
   git clone <link-repo-anda>
   ```
2. Buat database baru di MySQL dengan nama `smart_event_campus`.
3. Import file `database.sql` ke dalam database tersebut.
4. Sesuaikan konfigurasi koneksi database pada file `config.php`:
   ```php
   $host = "localhost";
   $username = "root"; // Username database anda
   $password = ""; // Password database anda
   $database = "smart_event_campus";
   ```
5. Akses aplikasi melalui browser web: `http://localhost/folder-aplikasi`

## Penggunaan
- **Akses Publik**: Buka halaman utama (`index.php`) untuk melihat daftar kegiatan.
- **Login Admin**: Klik tombol "Admin Login" pada navbar atau akses langsung `login.php`.
- **Kredensial Default**:
  - **Username**: admin
  - **Password**: password123

## Dokumentasi
Dokumentasi teknis lengkap dan panduan screenshot dapat ditemukan di dalam file `Dokumentasi.md` (dapat diexport menjadi PDF).
