# Quick Deployment Guide - E-Prescription System

## 🚀 Deployment Options

### 1. **Local Development** (Recommended for testing)
### 2. **Shared Hosting** (cPanel, Hostinger, etc.)
### 3. **VPS/Cloud Server** (DigitalOcean, AWS, etc.)
### 4. **Docker** (Containerized deployment)
### 5. **Heroku** (Cloud platform)

---

## 📋 Prerequisites

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Git
- Web server (Apache/Nginx)

---

## 🏠 1. Local Development Deployment

### Step 1: Clone/Setup Project
```bash
# Jika sudah ada project
cd e-prescriptions

# Install dependencies
composer install
```

### Step 2: Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env file dengan konfigurasi database lokal
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eprescription
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Setup Database
```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link
```

### Step 4: Start Server
```bash
php artisan serve
```

**Akses:** http://localhost:8000

**Default Users:**
- Admin: admin@eprescription.com / password
- Dokter: dokter@eprescription.com / password
- Apoteker: apoteker@eprescription.com / password

---

## 🌐 2. Shared Hosting Deployment

### Step 1: Prepare Files
```bash
# Gunakan script deployment
deploy.bat shared
```

### Step 2: Upload ke Hosting
1. Upload file `eprescription-deployment.zip` ke hosting
2. Extract file di folder `public_html` atau `www`
3. Buat database MySQL di cPanel

### Step 3: Configure Database
1. Edit file `.env` dengan kredensial database hosting
2. Set `APP_URL` ke domain Anda
3. Set `APP_ENV=production`
4. Set `APP_DEBUG=false`

### Step 4: Run Commands via SSH/cPanel Terminal
```bash
cd /path/to/your/project
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 775 storage/logs storage/framework
```

---

## 🖥️ 3. VPS/Cloud Server Deployment

### Step 1: Server Setup (Ubuntu 20.04+)
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install dependencies
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-cli unzip git composer -y
```

### Step 2: Setup MySQL
```bash
sudo mysql_secure_installation
sudo mysql -u root -p

# Di dalam MySQL
CREATE DATABASE eprescription;
CREATE USER 'eprescription'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON eprescription.* TO 'eprescription'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 3: Deploy Application
```bash
cd /var/www
sudo git clone https://github.com/your-repo/eprescription.git
sudo chown -R www-data:www-data eprescription
cd eprescription

# Install dependencies
composer install --no-dev --optimize-autoloader

# Configure environment
cp .env.example .env
nano .env  # Edit dengan kredensial database dan domain
```

### Step 4: Setup Application
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 5: Configure Nginx
```bash
# Gunakan script deployment untuk generate config
deploy.bat vps

# Copy config ke Nginx
sudo cp nginx-eprescription.conf /etc/nginx/sites-available/eprescription
sudo ln -s /etc/nginx/sites-available/eprescription /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Step 6: Setup SSL (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

---

## 🐳 4. Docker Deployment

### Step 1: Prepare Docker Files
```bash
# Generate Docker files
deploy.bat docker
```

### Step 2: Configure Environment
Edit `docker-compose.yml` dengan kredensial database yang aman.

### Step 3: Build and Run
```bash
# Build dan start containers
docker-compose up -d

# Setup application
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

**Akses:** http://localhost:8000

---

## ☁️ 5. Heroku Deployment

### Step 1: Install Heroku CLI
Download dari: https://devcenter.heroku.com/articles/heroku-cli

### Step 2: Login dan Deploy
```bash
# Login ke Heroku
heroku login

# Deploy menggunakan script
deploy.bat heroku
```

### Step 3: Configure Environment Variables
```bash
# Set email configuration
heroku config:set MAIL_MAILER=smtp
heroku config:set MAIL_HOST=smtp.gmail.com
heroku config:set MAIL_PORT=587
heroku config:set MAIL_USERNAME=your-email@gmail.com
heroku config:set MAIL_PASSWORD=your-app-password
```

---

## 🔧 Post-Deployment Configuration

### 1. Email Configuration
Edit `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="E-Prescription System"
```

### 2. Security Settings
```env
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

### 3. Database Optimization
```bash
# Optimize database
php artisan migrate:status
php artisan db:show

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🛠️ Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   ```bash
   # Check Laravel logs
   tail -f storage/logs/laravel.log
   
   # Check permissions
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Database Connection Error**
   - Verify database credentials in `.env`
   - Check if MySQL service is running
   - Verify database exists

3. **Email Not Sending**
   - Check SMTP configuration
   - Verify Gmail App Password
   - Check firewall settings

4. **Permission Denied**
   ```bash
   # Set proper permissions
   sudo chown -R www-data:www-data /var/www/eprescription
   sudo chmod -R 755 /var/www/eprescription
   sudo chmod -R 775 /var/www/eprescription/storage
   ```

### Useful Commands:
```bash
# Check Laravel status
php artisan about

# Generate new application key
php artisan key:generate

# Check storage link
php artisan storage:link

# Clear all caches
php artisan optimize:clear
```

---

## 📞 Support

Jika mengalami masalah:

1. **Check Logs:** `storage/logs/laravel.log`
2. **Verify Requirements:** PHP 8.1+, MySQL 8.0+
3. **Check Permissions:** File dan folder permissions
4. **Database:** Koneksi dan kredensial
5. **Email:** SMTP configuration

**Contact:** Buat issue di repository atau hubungi developer dengan detail error yang muncul.

---

## 🎯 Quick Start Checklist

- [ ] Environment file configured
- [ ] Database created and configured
- [ ] Dependencies installed
- [ ] Migrations run
- [ ] Database seeded
- [ ] Application key generated
- [ ] Storage link created
- [ ] Permissions set correctly
- [ ] Web server configured
- [ ] SSL certificate installed (production)
- [ ] Email configured
- [ ] Application accessible via browser
- [ ] Default users can login
- [ ] All features working correctly 