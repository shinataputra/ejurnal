# eJurnal - Sistem Jurnal Mengajar SMKN 1 Probolinggo

Aplikasi web untuk manajemen jurnal mengajar guru dengan fitur admin dashboard, rekapitulasi, dan pengaturan sekolah.

**Dikembangkan oleh:** ICT - SMKN 1 PROBOLINGGO

---

## 📋 Daftar Isi

- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Fitur](#fitur)
- [Akun Default](#akun-default)
- [Struktur Folder](#struktur-folder)

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

### Untuk Guru (Teacher)

- ✅ Dashboard dengan ringkasan jurnal
- ✅ Tambah jurnal mengajar (tanggal, kelas, mapel, jam, materi, catatan)
- ✅ Lihat daftar jurnal
- ✅ Edit dan hapus jurnal
- ✅ Rekapitulasi harian dan bulanan
- ✅ Cetak rekapitulasi

### Untuk Admin

- ✅ Dashboard admin
- ✅ Kelola pengguna (guru & admin)
- ✅ Kelola kelas
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

## 📁 Struktur Folder

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

### Error 500

- **Solusi:** Periksa konfigurasi PHP dan error log

---

## 📄 Lisensi

Dikembangkan untuk SMKN 1 Probolinggo

---

## 👨‍💻 Support

Hubungi ICT - SMKN 1 PROBOLINGGO
