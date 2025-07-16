# 📋 Requirements E-Prescription System

## 🔧 System Requirements

### **Server Requirements:**
- **PHP**: 8.2 atau lebih tinggi
- **MySQL**: 5.7+ atau MariaDB 10.2+
- **Composer**: 2.0+
- **Web Server**: Apache/Nginx
- **Memory**: Minimum 512MB RAM
- **Storage**: Minimum 1GB free space

### **PHP Extensions yang Harus Aktif:**
```ini
extension=bcmath
extension=ctype
extension=curl
extension=dom
extension=fileinfo
extension=filter
extension=gd
extension=hash
extension=iconv
extension=json
extension=libxml
extension=mbstring
extension=openssl
extension=pcre
extension=pdo
extension=pdo_mysql
extension=phar
extension=session
extension=simplexml
extension=tokenizer
extension=xml
extension=xmlreader
extension=xmlwriter
extension=zip
```

### **Khusus untuk Windows (XAMPP):**
Pastikan ekstensi berikut aktif di `php.ini`:
```ini
extension=zip
extension=gd
extension=mbstring
extension=openssl
extension=pdo_mysql
```

## 📦 Library Dependencies

### **Install Semua Dependencies:**
```bash
# Clone project
git clone [repository-url]
cd e-prescriptions

# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=e_prescriptions
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### **Library yang Digunakan:**

#### **1. Laravel Framework (^12.0)**
- Framework utama aplikasi
- Routing, authentication, database ORM

#### **2. Laravel Sanctum (^4.1)**
- API authentication
- Token-based authentication

#### **3. Laravel Tinker (^2.10)**
- REPL untuk Laravel
- Development tool

#### **4. Barryvdh Laravel DomPDF (^3.1)**
- Generate PDF dari HTML
- Untuk cetak resep dalam format PDF

#### **5. Spatie Simple Excel (^3.7)**
- Export data ke Excel/CSV
- Untuk export data resep

### **Development Dependencies:**
- **FakerPHP/Faker**: Generate fake data untuk testing
- **Laravel Pint**: Code style fixer
- **Mockery**: Mocking framework untuk testing
- **Nunomaduro/Collision**: Better error reporting
- **PHPUnit**: Unit testing framework

## 🚀 Setup Instructions

### **1. Server Setup (XAMPP/WAMP/LAMP):**
```bash
# Pastikan PHP 8.2+ terinstall
php -v

# Pastikan Composer terinstall
composer -V

# Pastikan MySQL berjalan
mysql --version
```

### **2. Project Setup:**
```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Clear cache
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### **3. Web Server Configuration:**
```apache
# Apache (.htaccess sudah ada di public/)
# Pastikan mod_rewrite aktif

# Nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### **4. File Permissions (Linux/Mac):**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### **5. Database Setup:**
```sql
-- Buat database
CREATE DATABASE e_prescriptions CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Import data awal (jika ada)
mysql -u root -p e_prescriptions < database/db_resep.sql
```

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

## 📱 Browser Support
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

## 🔐 Security Notes
- Selalu gunakan HTTPS di production
- Update dependencies secara berkala
- Backup database secara rutin
- Monitor error logs

## 📞 Support
Jika ada masalah, cek:
1. Error logs di `storage/logs/laravel.log`
2. Browser console untuk JavaScript errors
3. Network tab untuk HTTP errors
4. Database connection status 