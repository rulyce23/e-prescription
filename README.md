# 🏥 E-Prescription System

Sistem manajemen resep digital yang modern, aman, dan mudah digunakan untuk rumah sakit, klinik, dan apotek.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
- [Library & Dependencies](#-library--dependencies)
- [Screenshot](#-screenshot)
- [Instalasi](#-instalasi)
- [Konfigurasi](#-konfigurasi)
- [Panduan Penggunaan](#-panduan-penggunaan)
- [API Documentation](#-api-documentation)
- [Struktur Database](#-struktur-database)
- [Troubleshooting](#-troubleshooting)
- [Kontribusi](#-kontribusi)
- [Lisensi](#-lisensi)

## ✨ Fitur Utama

### 🔐 **Keamanan & Autentikasi**
- ✅ Login dengan email verification
- ✅ Password reset yang aman
- ✅ Role-based access control (RBAC)
- ✅ Rate limiting untuk mencegah brute force
- ✅ CSRF protection
- ✅ Input validation dan sanitization

### 👥 **Manajemen User**
- ✅ 5 Role: Administrator, Dokter, Apoteker, Farmasi, Pasien
- ✅ Dashboard yang berbeda untuk setiap role
- ✅ Permissions yang fleksibel
- ✅ Manajemen apotek untuk apoteker/farmasi

### 💊 **Manajemen Resep**
- ✅ Resep non-racikan dan racikan
- ✅ Draft preview sebelum submit
- ✅ Workflow: Pending → Diproses → Selesai
- ✅ Validasi stok otomatis
- ✅ Pengurangan stok otomatis saat approval
- ✅ PDF generation untuk print
- ✅ Export Excel/CSV untuk data resep
- ✅ Filter data berdasarkan apotek dan status
- ✅ Nomor antrian otomatis per apotek

### 📊 **Master Data**
- ✅ Manajemen Signa (instruksi penggunaan obat)
- ✅ Manajemen Obatalkes (obat dan alat kesehatan)
- ✅ Monitoring stok dengan alert
- ✅ Manajemen Apotek

### 📱 **Interface**
- ✅ Responsive design dengan Bootstrap 5
- ✅ Modern UI/UX dengan animasi
- ✅ Interactive JavaScript untuk form dinamis
- ✅ Real-time validation
- ✅ Notification system

### 🔌 **API Support**
- ✅ RESTful API untuk semua fitur
- ✅ Laravel Sanctum authentication
- ✅ JSON responses
- ✅ Postman collection tersedia

### 📧 **Notifikasi**
- ✅ WhatsApp notification (Fonnte API)
- ✅ Internal notification system
- ✅ Email notifications

## 📦 Library & Dependencies

### **Core Framework**
```bash
laravel/framework:^12.0          # Laravel Framework
laravel/sanctum:^4.1            # API Authentication
laravel/tinker:^2.10            # REPL for Laravel
```

### **PDF Generation**
```bash
barryvdh/laravel-dompdf:^3.1    # Generate PDF from HTML
```
**Fitur:** Generate PDF resep yang bisa langsung dicetak atau didownload.

### **Excel/CSV Export**
```bash
spatie/simple-excel:^3.7        # Export data to Excel/CSV
```
**Fitur:** Export data resep ke format Excel (.xlsx) atau CSV dengan filter berdasarkan role user.

### **Development Dependencies**
```bash
fakerphp/faker:^1.23            # Generate fake data for testing
laravel/pint:^1.13              # Code style fixer
mockery/mockery:^1.6            # Mocking framework for testing
nunomaduro/collision:^8.6       # Better error reporting
phpunit/phpunit:^11.5.3         # Unit testing framework
```

### **PHP Extensions Required**
```ini
extension=zip          # Untuk export Excel/CSV
extension=gd           # Untuk generate PDF
extension=mbstring     # Untuk string handling
extension=openssl      # Untuk security
extension=pdo_mysql    # Untuk database
extension=curl         # Untuk HTTP requests
extension=json         # Untuk JSON handling
extension=xml          # Untuk XML processing
```

## 🖼️ Screenshot

### Login Page
![Login](https://via.placeholder.com/800x400/667eea/ffffff?text=Login+Page)

### Dashboard
![Dashboard](https://via.placeholder.com/800x400/28a745/ffffff?text=Dashboard)

### Prescription Form
![Prescription](https://via.placeholder.com/800x400/ffc107/000000?text=Prescription+Form)

## 🚀 Instalasi

### Prerequisites
- PHP 8.2 atau lebih tinggi
- Composer 2.0+
- MySQL 5.7+ atau MariaDB 10.2+
- Web Server (Apache/Nginx)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd e-prescriptions
```

2. **Install Dependencies**
```bash
composer install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi Database**
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_prescriptions
DB_USERNAME=root
DB_PASSWORD=
```

5. **Setup Database**
```bash
php artisan migrate:fresh --seed
```

6. **Clear Cache**
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

7. **Start Server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## ⚙️ Konfigurasi

### Email Configuration (Gmail SMTP)

1. **Enable 2-Factor Authentication** di Google Account
2. **Generate App Password**:
   - Buka Google Account → Security → 2-Step Verification
   - Klik "App passwords"
   - Generate password untuk "Mail"
3. **Update .env**:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@eprescription.com
MAIL_FROM_NAME="E-Prescription System"
```

### WhatsApp Configuration (Fonnte API)

1. **Daftar di Fonnte** dan dapatkan API token
2. **Update .env**:
```env
FONNTE_API_TOKEN=your-fonnte-token
FONNTE_DEVICE_ID=your-device-id
```

### File Permissions
```bash
# Linux/Mac
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Windows
# Pastikan folder tidak di-lock oleh antivirus
```

## 📖 Panduan Penggunaan

### 🔑 Login & Registrasi

#### **Default Users**
| Email | Password | Role |
|-------|----------|------|
| `admin@eprescription.com` | `password` | Administrator |
| `dokter@eprescription.com` | `password` | Dokter |
| `apoteker@eprescription.com` | `password` | Apoteker |
| `farmasi@eprescription.com` | `password` | Farmasi |
| `pasien@eprescription.com` | `password` | Pasien |

#### **Email Verification**
1. Login pertama kali akan diarahkan ke halaman verifikasi email
2. Cek email Anda untuk link verifikasi
3. Klik link verifikasi untuk mengaktifkan akun
4. Login kembali dengan akun yang sudah diverifikasi

#### **Password Reset**
1. Klik "Lupa Password?" di halaman login
2. Masukkan email yang terdaftar
3. Cek email untuk link reset password
4. Masukkan password baru

### 👨‍⚕️ **Role: Dokter**

#### **Membuat Resep**
1. Login sebagai dokter
2. Klik "Buat Resep Baru"
3. Isi informasi pasien:
   - Nama pasien (auto-filled)
   - Pilih apotek
   - Keluhan dan diagnosa
4. **Tambah Obat Non-Racikan**:
   - Pilih obat/alkes dari dropdown
   - Pilih signa (instruksi penggunaan)
   - Masukkan jumlah
   - Aturan pakai
5. **Tambah Obat Racikan** (opsional):
   - Klik "Tambah Racikan"
   - Beri nama racikan
   - Pilih signa
   - Tambah komponen obat
6. **Preview Resep**:
   - Lihat preview di bagian bawah
   - Pastikan semua data benar
7. **Submit**:
   - Klik "Simpan Resep"

#### **Mengelola Resep**
- **Dashboard**: Lihat statistik dan resep terbaru
- **Daftar Resep**: Lihat semua resep yang dibuat
- **Filter**: Filter berdasarkan apotek dan status
- **Export**: Export data ke Excel/CSV
- **Print/PDF**: Generate dokumen untuk pasien

### 🏥 **Role: Apoteker/Farmasi**

#### **Menerima Resep**
1. Login sebagai apoteker/farmasi
2. Dashboard akan menampilkan resep yang pending
3. Klik "Proses" pada resep yang akan diproses
4. Resep status berubah menjadi "Diproses"
5. Klik "Selesai" untuk menyelesaikan resep
6. Sistem akan mengirim notifikasi WhatsApp ke pasien

#### **Monitoring Stok**
- Dashboard menampilkan alert stok menipis
- Lihat daftar obat dengan stok ≤ 10
- Monitor obat yang habis stok

#### **Export Data**
- Export semua resep apotek ke Excel/CSV
- Filter berdasarkan status (pending, diproses, selesai)

### 👨‍💼 **Role: Administrator**

#### **Manajemen Master Data**
1. **Signa Management**:
   - Tambah/edit/hapus signa
   - Signa adalah instruksi penggunaan obat
2. **Obatalkes Management**:
   - Tambah/edit/hapus obat dan alkes
   - Update stok
   - Set harga dan informasi lainnya
3. **Apotek Management**:
   - Tambah/edit/hapus apotek
   - Assign apoteker/farmasi ke apotek

#### **User Management**
- Lihat semua user dalam sistem
- Monitor aktivitas user
- Akses ke semua fitur

#### **Export Data**
- Export semua data resep dengan filter apotek dan status
- Akses ke semua data tanpa batasan

### 👤 **Role: Pasien**

#### **Melihat Resep**
- Dashboard menampilkan resep pribadi
- Lihat status resep (pending, diproses, selesai)
- Download PDF resep
- Export data resep pribadi ke Excel/CSV

#### **Notifikasi**
- Terima notifikasi WhatsApp saat resep selesai
- Terima notifikasi internal di aplikasi

## 🔌 API Documentation

### Authentication
```bash
# Login
POST /api/login
{
    "email": "user@example.com",
    "password": "password"
}

# Response
{
    "token": "1|abc123...",
    "user": {...}
}
```

### Prescriptions
```bash
# Get all prescriptions
GET /api/prescriptions
Authorization: Bearer {token}

# Create prescription
POST /api/prescriptions
Authorization: Bearer {token}
{
    "nama_pasien": "John Doe",
    "apotek_id": 1,
    "keluhan": "Sakit kepala",
    "diagnosa": "Migrain",
    "items": [...],
    "racikan": [...]
}
```

### Export Data
```bash
# Export prescriptions
GET /api/prescriptions/export?apotek_id=1&status=selesai
Authorization: Bearer {token}
```

## 🗄️ Struktur Database

### **Tables:**
- `users` - User accounts dan roles
- `apotek` - Data apotek
- `resep` - Data resep
- `resep_items` - Item obat non-racikan
- `resep_racikan` - Data racikan
- `resep_racikan_items` - Item obat racikan
- `obatalkes_m` - Master data obat dan alkes
- `signa_m` - Master data signa
- `notifications` - Internal notifications
- `password_reset_tokens` - Password reset tokens
- `personal_access_tokens` - API tokens

### **Relationships:**
- User → Apotek (belongsTo)
- User → Resep (hasMany)
- Resep → Apotek (belongsTo)
- Resep → Items (hasMany)
- Resep → Racikan (hasMany)
- Racikan → RacikanItems (hasMany)

## 🔍 Troubleshooting

### **Common Issues:**

#### **1. Composer Memory Limit:**
```bash
COMPOSER_MEMORY_LIMIT=-1 composer install
```

#### **2. Permission Denied:**
```bash
# Linux/Mac
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Windows
# Pastikan folder tidak di-lock oleh antivirus
```

#### **3. Database Connection:**
- Cek konfigurasi di `.env`
- Pastikan MySQL service berjalan
- Cek username/password database

#### **4. Export Excel Error:**
- Pastikan ekstensi `zip` aktif di PHP
- Gunakan format CSV untuk kompatibilitas lebih baik

#### **5. PDF Generation Error:**
- Pastikan ekstensi `gd` aktif di PHP
- Cek permission folder storage

#### **6. WhatsApp Notification Error:**
- Cek konfigurasi Fonnte API di `.env`
- Pastikan device status online di Fonnte
- Cek format nomor telepon (harus 62xxx)

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

Jika ada masalah, cek:
1. Error logs di `storage/logs/laravel.log`
2. Browser console untuk JavaScript errors
3. Network tab untuk HTTP errors
4. Database connection status

**Dokumentasi Lengkap:**
- [Requirements](REQUIREMENTS.md) - System requirements dan setup
- [Feature Updates](FEATURE_UPDATES.md) - Dokumentasi fitur baru
- [API Collection](E-Prescription-API.postman_collection.json) - Postman collection

---

**Made with ❤️ using Laravel Framework**
