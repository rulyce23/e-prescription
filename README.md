<<<<<<< HEAD
# e-prescription
=======
# 🏥 E-Prescription System

Sistem manajemen resep digital yang modern, aman, dan mudah digunakan untuk rumah sakit, klinik, dan apotek.

![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange.svg)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple.svg)

## 📋 Daftar Isi

- [Fitur Utama](#-fitur-utama)
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
- ✅ 4 Role: Administrator, Dokter, Apoteker, Pasien
- ✅ Dashboard yang berbeda untuk setiap role
- ✅ Permissions yang fleksibel

### 💊 **Manajemen Resep**
- ✅ Resep non-racikan dan racikan
- ✅ Draft preview sebelum submit
- ✅ Workflow approval: Draft → Pending → Approved → Completed
- ✅ Validasi stok otomatis
- ✅ Pengurangan stok otomatis saat approval
- ✅ PDF generation untuk print

### 📊 **Master Data**
- ✅ Manajemen Signa (instruksi penggunaan obat)
- ✅ Manajemen Obatalkes (obat dan alat kesehatan)
- ✅ Monitoring stok dengan alert

### 📱 **Interface**
- ✅ Responsive design dengan Bootstrap 5
- ✅ Modern UI/UX dengan animasi
- ✅ Interactive JavaScript untuk form dinamis
- ✅ Real-time validation

### 🔌 **API Support**
- ✅ RESTful API untuk semua fitur
- ✅ Laravel Sanctum authentication
- ✅ JSON responses
- ✅ Postman collection tersedia

## 🖼️ Screenshot

### Login Page
![Login](https://via.placeholder.com/800x400/667eea/ffffff?text=Login+Page)

### Dashboard
![Dashboard](https://via.placeholder.com/800x400/28a745/ffffff?text=Dashboard)

### Prescription Form
![Prescription](https://via.placeholder.com/800x400/ffc107/000000?text=Prescription+Form)

## 🚀 Instalasi

### Prerequisites
- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 5.7 atau lebih tinggi (XAMPP/WAMP/MAMP)
- Node.js dan NPM (untuk frontend assets)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd e-prescriptions
```

2. **Install Dependencies**
```bash
composer install
npm install
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
DB_DATABASE=eprescription
DB_USERNAME=root
DB_PASSWORD=
```

5. **Setup Database**
```bash
php artisan migrate:fresh --seed
```

6. **Build Assets (Optional)**
```bash
npm run build
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

### File Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

## 📖 Panduan Penggunaan

### 🔑 Login & Registrasi

#### **Default Users**
| Email | Password | Role |
|-------|----------|------|
| `admin@eprescription.com` | `password` | Administrator |
| `dokter@eprescription.com` | `password` | Dokter |
| `apoteker@eprescription.com` | `password` | Apoteker |
| `pasien1@eprescription.com` | `password` | Pasien |

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
   - Nama pasien
   - Tanggal resep
   - Catatan (opsional)
4. **Tambah Obat Non-Racikan**:
   - Pilih obat/alkes dari dropdown
   - Pilih signa (instruksi penggunaan)
   - Masukkan jumlah
5. **Tambah Obat Racikan** (opsional):
   - Klik "Tambah Racikan"
   - Beri nama racikan
   - Pilih signa
   - Tambah komponen obat
6. **Preview Resep**:
   - Lihat preview di bagian bawah
   - Pastikan semua data benar
7. **Submit**:
   - "Simpan Draft" untuk menyimpan sementara
   - "Ajukan Resep" untuk langsung approve

#### **Mengelola Resep**
- **Dashboard**: Lihat statistik dan resep terbaru
- **Daftar Resep**: Lihat semua resep yang dibuat
- **Edit Resep**: Edit resep yang masih draft
- **Print/PDF**: Generate dokumen untuk pasien

### 🏥 **Role: Apoteker**

#### **Menerima Resep**
1. Login sebagai apoteker
2. Dashboard akan menampilkan resep yang sudah approved
3. Klik "Terima Resep" pada resep yang akan diproses
4. Sistem akan mengurangi stok otomatis
5. Resep status berubah menjadi "Completed"

#### **Monitoring Stok**
- Dashboard menampilkan alert stok menipis
- Lihat daftar obat dengan stok ≤ 10
- Monitor obat yang habis stok

### 👨‍💼 **Role: Administrator**

#### **Manajemen Master Data**
1. **Signa Management**:
   - Tambah/edit/hapus signa
   - Signa adalah instruksi penggunaan obat
2. **Obatalkes Management**:
   - Tambah/edit/hapus obat dan alkes
   - Update stok
   - Set harga dan informasi lainnya

#### **User Management**
- Lihat semua user dalam sistem
- Monitor aktivitas user
- Akses ke semua fitur

### 👤 **Role: Pasien**

#### **Melihat Resep**
- Dashboard menampilkan resep pribadi
- Lihat status resep (draft, pending, approved, completed)
- Download PDF resep

### 📋 **Workflow Resep**

```
Draft → Pending → Approved → Completed
  ↓        ↓         ↓         ↓
Dokter   Dokter   Admin/    Apoteker
         Submit    Dokter   Receive
```

1. **Draft**: Resep disimpan sementara
2. **Pending**: Resep diajukan untuk approval
3. **Approved**: Resep disetujui, siap diproses
4. **Completed**: Resep sudah diterima apoteker

## 🔌 API Documentation

### Authentication

#### Login
```http
POST /api/login
Content-Type: application/json

{
    "email": "dokter@eprescription.com",
    "password": "password"
}
```

#### Response
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Dr. Sarah Johnson",
            "email": "dokter@eprescription.com",
            "role": "dokter"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Prescriptions

#### Get All Prescriptions
```http
GET /api/resep
Authorization: Bearer {token}
```

#### Create Prescription
```http
POST /api/resep
Authorization: Bearer {token}
Content-Type: application/json

{
    "nama_pasien": "John Doe",
    "tanggal_resep": "2024-01-15",
    "catatan": "Pasien alergi penicillin",
    "items": [
        {
            "obatalkes_id": 1,
            "signa_id": 1,
            "qty": 10
        }
    ],
    "racikan": [
        {
            "nama_racikan": "Racikan Batuk",
            "signa_id": 2,
            "items": [
                {
                    "obatalkes_id": 2,
                    "qty": 5
                }
            ]
        }
    ],
    "action": "submit"
}
```

#### Approve Prescription
```http
PATCH /api/resep/{id}/approve
Authorization: Bearer {token}
```

#### Reject Prescription
```http
PATCH /api/resep/{id}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
    "alasan": "Stok tidak mencukupi"
}
```

#### Receive Prescription
```http
PATCH /api/resep/{id}/receive
Authorization: Bearer {token}
```

### Master Data

#### Get Signa
```http
GET /api/signa
Authorization: Bearer {token}
```

#### Create Signa
```http
POST /api/signa
Authorization: Bearer {token}
Content-Type: application/json

{
    "signa_nama": "3x1 tablet sehari"
}
```

#### Get Obatalkes
```http
GET /api/obatalkes
Authorization: Bearer {token}
```

#### Create Obatalkes
```http
POST /api/obatalkes
Authorization: Bearer {token}
Content-Type: application/json

{
    "obatalkes_nama": "Paracetamol 500mg",
    "stok": 100,
    "harga": 5000
}
```

## 🗄️ Struktur Database

### Users Table
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'dokter', 'apoteker', 'pasien') DEFAULT 'dokter',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Resep Table
```sql
CREATE TABLE resep (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama_pasien VARCHAR(255) NOT NULL,
    tanggal_resep DATE NOT NULL,
    catatan TEXT NULL,
    items JSON NULL,
    racikan JSON NULL,
    status ENUM('draft', 'pending', 'approved', 'rejected', 'completed') DEFAULT 'draft',
    user_id BIGINT NOT NULL,
    approved_by BIGINT NULL,
    approved_at TIMESTAMP NULL,
    rejected_by BIGINT NULL,
    rejected_at TIMESTAMP NULL,
    alasan_reject TEXT NULL,
    received_by BIGINT NULL,
    received_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Signa M Table
```sql
CREATE TABLE signa_m (
    signa_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    signa_nama VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Obatalkes M Table
```sql
CREATE TABLE obatalkes_m (
    obatalkes_id BIGINT PRIMARY KEY AUTO_INCREMENT,
    obatalkes_nama VARCHAR(255) NOT NULL,
    stok DECIMAL(10,2) DEFAULT 0,
    harga DECIMAL(10,2) DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## 🔧 Troubleshooting

### Common Issues

#### 1. **Database Connection Error**
```bash
# Pastikan MySQL berjalan
# Cek credentials di .env
# Pastikan database exists
php artisan migrate:status
```

#### 2. **Email Not Sending**
```bash
# Cek Gmail SMTP settings
# Pastikan app password benar
# Verifikasi 2FA enabled
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com'); });
```

#### 3. **Permission Errors**
```bash
# Set proper file permissions
chmod -R 775 storage bootstrap/cache
# Pastikan web server punya write access
```

#### 4. **Migration Errors**
```bash
# Drop dan recreate database
php artisan migrate:fresh --seed
```

#### 5. **Class Not Found Errors**
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### Logs
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check error logs
tail -f storage/logs/error.log
```

## 🤝 Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

### Coding Standards
- Ikuti PSR-12 coding standards
- Gunakan meaningful variable names
- Tambahkan comments untuk complex logic
- Write unit tests untuk new features

## 📄 Lisensi

Distributed under the MIT License. See `LICENSE` for more information.

## 📞 Support

- **Email**: support@eprescription.com
- **Documentation**: [Link to documentation]
- **Issues**: [GitHub Issues](https://github.com/username/e-prescriptions/issues)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The web framework
- [Bootstrap](https://getbootstrap.com/) - CSS framework
- [Font Awesome](https://fontawesome.com/) - Icons
- [DomPDF](https://github.com/barryvdh/laravel-dompdf) - PDF generation

---

**Made with ❤️ for better healthcare management**
>>>>>>> 8cf5052 (Initial commit: E-Prescription System with Laravel)
