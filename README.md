# eJurnal - Sistem Jurnal Mengajar SMKN 1 Probolinggo

Aplikasi web untuk manajemen jurnal mengajar guru dengan fitur admin dashboard, rekapitulasi, dan pengaturan sekolah.

**Dikembangkan oleh:** ICT - SMKN 1 PROBOLINGGO

**Versi:** 2.1 (Enhanced Dashboard & PDF Improvements)

---

## 🆕 Update Terbaru (v2.1)

- **Dashboard Admin Redesigned** - Masonry layout responsif dengan data density tinggi, professional UI tanpa dekorasi berlebih
- **Paginasi Pengguna** - Tampilkan 25 guru per halaman dengan navigasi intuitif
- **Pencarian Pengguna** - Cari guru berdasarkan nama secara real-time
- **PDF Improvements** - Kop surat baru, NIP ditampilkan penuh, spacing profesional pada tanda tangan, margin top 1.5cm
- **Jenjang Kelas** - Otomatis mengenali dan mengelompokkan kelas per jenjang (X, XI, XII)

## 📋 Daftar Isi

- [Update Terbaru](#-update-terbaru-v21)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Fitur Utama](#-fitur-utama)
- [Akun Default](#akun-default)
- [Struktur Folder](#-struktur-folder)

---

## 💻 Persyaratan Sistem

Sebelum menginstal, pastikan Anda memiliki:

- **PHP 8.0+** (dengan ekstensi: PDO, PDO MySQL, mbstring, fileinfo)
- **MySQL 5.7+** atau **MariaDB 10.3+**
- **Web Server** (Apache, Nginx, atau PHP Built-in Server untuk development)
- **Git** (untuk clone repository)

### Verifikasi Instalasi

```bash
php --version
mysql --version
```

---

## 📦 Instalasi

### 1. Clone atau Download Repository

```bash
# Clone dari Git
git clone <repository-url> ejurnal
cd ejurnal

# Atau ekstrak file ZIP
```

### 2. Buat Database

Buka MySQL/MariaDB console:

```bash
mysql -u root -p
```

Jalankan perintah:

```sql
CREATE DATABASE ejurnal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ejurnal;
```

### 3. Import Database Schema

```bash
# Dari folder project
mysql -u root -p ejurnal < db/schema.sql
```

Atau jalankan melalui PHP:

```bash
php db/migrate.php
```

Atau jalankan melalui Docker:

```bash
docker exec -it ejurnal-app php db/migrate.php
```

### 4. Seed Data (Optional)

Untuk membuat data awal (admin + teacher + sample data):

```bash
php db/seed.php
```

---

## ⚙️ Konfigurasi

### 1. Edit File `config.php`

Buka file `config.php` dan sesuaikan konfigurasi database:

```php
<?php
$config = [
    'db' => [
        'host' => '127.0.0.1',      // Hostname MySQL
        'name' => 'ejurnal',         // Nama database
        'user' => 'root',            // Username MySQL
        'pass' => 'root'             // Password MySQL
    ]
];
```

**Contoh untuk berbagai setup:**

- **Local Development (XAMPP/WAMP):**

  ```php
  'user' => 'root',
  'pass' => ''  // Biasanya kosong
  ```

- **Production Server:**
  ```php
  'host' => 'your-server.com',
  'user' => 'db_user',
  'pass' => 'secure_password'
  ```

### 2. Buat Folder Upload

```bash
# Windows
mkdir assets\uploads

# Linux/Mac
mkdir -p assets/uploads
chmod 755 assets/uploads
```

---

## 🚀 Menjalankan Aplikasi

### Opsi 1: PHP Built-in Server (Development)

```bash
# Di folder project
php -S localhost:8000
```

Akses aplikasi: **http://localhost:8000**

### Opsi 2: Apache (Production)

1. Copy folder project ke `htdocs` (XAMPP) atau `www` (WAMP)
2. Akses via `http://localhost/ejurnal`

### Opsi 3: Nginx

Buat konfigurasi:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/ejurnal;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

## ✨ Fitur Utama

### Dashboard Admin (Redesigned)

- ✅ **Masonry Layout** - Tampilan kartu responsif yang menyesuaikan tinggi konten
- ✅ **Ringkasan Statistik** - Total guru, kelas, tahun akademik aktif
- ✅ **Ringkasan Tugas** - Status menunggu, terverifikasi, ditolak
- ✅ **Ringkasan Jurnal** - Total entri dan data bulan berjalan dengan top guru
- ✅ **Aktivitas Terbaru** - Jurnal dan tugas terbaru
- ✅ User Interface profesional, data-dense, tanpa gradien dekoratif

### Untuk Guru (Teacher)

- ✅ Dashboard dengan ringkasan jurnal
- ✅ Tambah jurnal mengajar (tanggal, kelas, mapel, jam, materi, catatan)
- ✅ Lihat daftar jurnal
- ✅ Edit dan hapus jurnal
- ✅ Rekapitulasi harian dan bulanan
- ✅ **Cetak rekapitulasi ke PDF** dengan:
  - KOP surat SMKN 1 Probolinggo (Jalan Mastrip Nomor 357, Telepon (0335) 421121)
  - Identitas guru lengkap & NIP
  - Tanda tangan dengan spacing profesional
  - Top margin 1.5cm

### Untuk Admin

- ✅ **Dashboard admin** dengan masonry layout & data density tinggi
- ✅ **Kelola pengguna (guru & admin)** dengan:
  - Paginasi 25 baris per halaman
  - Pencarian nama guru real-time
  - Reset password & manajemen NIP
- ✅ Kelola kelas (otomatis mengenali jenjang X, XI, XII)
- ✅ Kelola mata pelajaran
- ✅ Kelola tahun pelajaran
- ✅ Pengaturan sekolah (ubah nama, upload logo)
- ✅ Responsive design (desktop & mobile)

---

## 👤 Akun Default

Setelah menjalankan `php db/seed.php`, gunakan akun berikut:

### Admin

- **Username:** `admin`
- **Password:** `admin123`

### Guru

- **Username:** `guru1`
- **Password:** `guru123`

**⚠️ Penting:** Ganti password default setelah login pertama kali!

---

## � Stack Teknologi

- **Backend:** PHP 8.0+ (MVC Architecture)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3 (Tailwind CSS)
- **PDF Generation:** DomPDF
- **Server:** Apache / Nginx / PHP Built-in
- **Session Management:** PHP Sessions

---

## �📁 Struktur Folder

```
ejurnal/
├── assets/                 # File statis
│   ├── css/
│   ├── js/
│   └── uploads/            # Logo sekolah
├── config.php              # Konfigurasi database
├── controllers/            # Controller
├── core/                   # Core framework
├── db/                     # Database
│   ├── schema.sql
│   ├── migrate.php
│   └── seed.php
├── models/                 # Model class
├── views/                  # Template
├── index.php               # Entry point
└── README.md
```

---

## 🔧 Troubleshooting

### Error: "Connection refused"

- **Solusi:** Pastikan MySQL running dan kredensial di `config.php` benar

### Error: "Table doesn't exist"

- **Solusi:** Jalankan `php db/migrate.php`

### Logo tidak muncul

- **Solusi:** Pastikan folder `assets/uploads/` ada dan writable

### NIP tidak muncul di PDF

- **Solusi:** Pastikan guru sudah memiliki NIP di profil (tidak kosong), atau login ulang agar session ter-update

### PDF margin & spacing tidak pas

- **Solusi:** Periksa aplikasi cetak/PDF viewer — setting margin printer mungkin override CSS halaman

### Error 500

- **Solusi:** Periksa konfigurasi PHP dan error log

---

## 📄 Lisensi

Dikembangkan untuk SMKN 1 Probolinggo

---

## 👨‍💻 Support

Hubungi ICT - SMKN 1 PROBOLINGGO
