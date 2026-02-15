# PT Samson SURE - Website Perusahaan

## Deskripsi

Website perusahaan PT Samson SURE yang menyajikan profil perusahaan, layanan, tim konsultan, serta informasi visi & misi. Dibangun dengan PHP dan MySQL menggunakan pendekatan modern, responsif, dan mudah dikelola melalui dashboard admin.

## Preview

![Preview](assets/bg.jpg)

## Fitur Utama

1. **Beranda (Home)**
   - Hero section, deskripsi, dan CTA
   - Konten dinamis dari database
2. **Tentang Kami (About)**
   - Daftar poin informasi (items) dan teks narasi
   - Gambar profil perusahaan
3. **Visi & Misi**
   - Konten visi dan misi perusahaan
   - Dukungan gambar per entri
4. **Manajemen Perusahaan**
   - Posisi, status, deskripsi, dan foto manajemen
5. **Layanan (Services)**
   - Daftar layanan dengan judul, deskripsi, dan gambar
6. **Konsultan (Consultants)**
   - Informasi konsultan dengan gambar
7. **Kontak**
   - Informasi kontak dan tautan WhatsApp
8. **Dashboard Admin**
   - Autentikasi admin, CRUD konten, unggah gambar
   - Logging aktivitas dan pembatasan percobaan login

## Struktur Database

Database terdiri dari tabel utama berikut (lihat `pt-samson-sure.sql`):

1. **accounts**
   - Akun admin: nama lengkap, email, password, role
2. **homes**
   - Konten beranda: judul, deskripsi, gambar
3. **abouts**
   - Items (JSON array {title, description}), teks, gambar
4. **visi_mission**
   - Tipe `vision` atau `mission`, deskripsi, gambar
5. **company_managements**
   - Posisi, status, deskripsi, gambar manajemen
6. **services**
   - Judul, deskripsi, gambar layanan
7. **consultants**
   - Judul, deskripsi, gambar konsultan
8. **contacts**
   - Judul, whatsapp, deskripsi, gambar kontak
9. **login_attempts**
   - Mencatat percobaan login per IP
10. **logs**

- Logging aksi sistem dan metadata user agent

## Teknologi yang Digunakan

- PHP 8+
- MySQL 8+
- HTML5
- CSS3
- JavaScript
- Tailwind CSS
- AOS (Animate On Scroll)
- jQuery + Toastr (notifikasi)
- Boxicons (ikon)

## Sistem Animasi & SEO

- AOS diinisialisasi di halaman publik untuk efek muncul saat scroll
- Notifikasi sukses/error di dashboard menggunakan Toastr
- Breadcrumb terstruktur (schema.org) untuk SEO di `js/breadchumb.js`

## Struktur Direktori

```
pt-samson-sure/
├── assets/             # Aset statis (gambar, favicon)
├── config/             # Konfigurasi database (`db.php`)
├── controllers/        # Layer akses data (CRUD)
├── dashboard/          # Panel admin (CRUD konten, logs, profil)
├── js/                 # Script umum, dashboard, toast, breadcrumb
├── layout/             # Header/Footer situs
├── public/             # Favicon dan aset publik
├── style/              # CSS global
├── uploads/            # Direktori upload terstruktur per modul
├── services/           # Halaman layanan publik
├── contact/            # Halaman kontak publik
├── vision-mission/     # Halaman visi & misi publik
├── index.php           # Halaman beranda
├── login.php           # Halaman login
├── register.php        # Halaman registrasi
├── logout.php          # Logout handler
└── pt-samson-sure.sql  # Struktur database
```

## Konfigurasi Server

### File .htaccess

Konfigurasi Apache untuk optimasi upload dan error:

```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
php_value max_input_vars 3000
php_value enable_post_data_reading On
php_value max_file_uploads 20
php_value file_uploads On
php_value upload_tmp_dir "/tmp"
ErrorDocument 404 /404.php
```

- Pastikan direktori `/tmp` dapat ditulis oleh web server
- Sesuaikan limit upload sesuai kebutuhan produksi

### Catatan Penting

- Pastikan modul `mod_rewrite` Apache sudah diaktifkan
- Jika menggunakan XAMPP/Laragon, modul ini biasanya sudah aktif secara default
- Jika mengalami masalah upload file, periksa permission direktori `/tmp`

## Instalasi

1. Clone repository
2. Import database `pt-samson-sure.sql`
3. Konfigurasi database di `config/db.php` (host, user, pass, nama DB)
4. Jalankan di web server (Apache/Nginx)

### Menjalankan dengan PHP Built-in Server

Untuk development, Anda dapat menggunakan PHP built-in server dengan cara:

```bash
# Masuk ke direktori proyek
cd pt-samson-sure

# Jalankan server PHP
php -S localhost:8000
```

Setelah menjalankan perintah di atas, website dapat diakses melalui browser di alamat: `http://localhost:8000`

### Menjalankan dengan XAMPP

1. Pastikan XAMPP sudah terinstal di komputer Anda
2. Pindahkan folder proyek ke direktori `htdocs` di XAMPP:
   - Windows: `C:\xampp\htdocs\pt-samson-sure`
   - Linux: `/opt/lampp/htdocs/pt-samson-sure`
   - macOS: `/Applications/XAMPP/htdocs/pt-samson-sure`
3. Jalankan Apache dan MySQL dari XAMPP Control Panel
4. Import database `pt-samson-sure.sql` melalui phpMyAdmin
5. Akses website melalui browser di alamat: `http://localhost/pt-samson-sure`

### Menjalankan dengan Laragon

1. Pastikan Laragon sudah terinstal di komputer Anda
2. Pindahkan folder proyek ke direktori `www` di Laragon:
   - Windows: `C:\laragon\www\pt-samson-sure`
3. Jalankan Laragon dan pastikan Apache dan MySQL berjalan
4. Import database `pt-samson-sure.sql` melalui HeidiSQL atau phpMyAdmin
5. Akses website melalui browser di alamat: `http://pt-samson-sure.test` atau `http://localhost/pt-samson-sure`

## Akses Admin

- Email: admin@gmail.com
- Password: (hubungi administrator)

## Kontribusi

Untuk berkontribusi pada proyek ini, silakan:

1. Fork repository
2. Buat branch fitur baru
3. Commit perubahan
4. Push ke branch
5. Buat Pull Request

## Lisensi

Hak Cipta © 2025 PT Samson SURE# pt-samson-sure
# PT.-Samson-Kapal-Yudhasamudera
