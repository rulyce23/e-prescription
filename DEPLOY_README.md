# 🚀 E-Prescription System - Deployment Guide

## 📋 Quick Start

### 🏠 Local Development (Recommended for testing)

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Setup Environment**
   ```bash
   copy .env.example .env
   # Edit .env with your database settings
   ```

3. **Setup Database**
   ```bash
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   php artisan storage:link
   ```

4. **Start Server**
   ```bash
   php artisan serve
   ```

**Access:** http://localhost:8000

**Default Users:**
- Admin: admin@eprescription.com / password
- Dokter: dokter@eprescription.com / password
- Apoteker: apoteker@eprescription.com / password
- Pasien: pasien@eprescription.com / password

---

## 🌐 Shared Hosting Deployment

### Step 1: Create Package
```bash
# Run quick deployment script
quick-deploy.bat
# Choose option 2 (Shared Hosting Package)
```

### Step 2: Upload & Setup
1. Upload `eprescription-shared.zip` to your hosting
2. Extract in `public_html` folder
3. Create MySQL database in cPanel
4. Edit `.env` file with database credentials
5. Run via SSH/cPanel Terminal:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

---

## 🖥️ VPS/Cloud Server Deployment

### Step 1: Generate Config Files
```bash
quick-deploy.bat
# Choose option 3 (VPS Configuration Files)
```

### Step 2: Server Setup
```bash
# Install dependencies (Ubuntu)
sudo apt update
sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql composer git

# Setup database
sudo mysql_secure_installation
sudo mysql -u root -p
CREATE DATABASE eprescription;
CREATE USER 'eprescription'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON eprescription.* TO 'eprescription'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Deploy application
cd /var/www
sudo git clone https://github.com/your-repo/eprescription.git
sudo chown -R www-data:www-data eprescription
cd eprescription
composer install --no-dev --optimize-autoloader
cp .env.example .env
# Edit .env with database credentials
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup Nginx
sudo cp nginx-eprescription.conf /etc/nginx/sites-available/eprescription
sudo ln -s /etc/nginx/sites-available/eprescription /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🐳 Docker Deployment

### Step 1: Generate Docker Files
```bash
quick-deploy.bat
# Choose option 4 (Docker Setup)
```

### Step 2: Run Containers
```bash
# Edit docker-compose.yml with secure passwords
docker-compose up -d

# Setup application
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

**Access:** http://localhost:8000

---

## ☁️ Heroku Deployment

### Step 1: Deploy
```bash
# Install Heroku CLI and login
heroku login

# Deploy
quick-deploy.bat
# Choose option 5 (Heroku Deploy)
```

### Step 2: Configure Email
```bash
heroku config:set MAIL_MAILER=smtp
heroku config:set MAIL_HOST=smtp.gmail.com
heroku config:set MAIL_PORT=587
heroku config:set MAIL_USERNAME=your-email@gmail.com
heroku config:set MAIL_PASSWORD=your-app-password
```

---

## ⚙️ Environment Configuration

### Required .env Settings
```env
APP_NAME="E-Prescription System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eprescription
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🛠️ Troubleshooting

### Common Issues:

1. **500 Internal Server Error**
   ```bash
   # Check logs
   tail -f storage/logs/laravel.log
   
   # Fix permissions
   chmod -R 755 storage bootstrap/cache
   ```

2. **Database Connection Error**
   - Verify database credentials in `.env`
   - Check if MySQL service is running
   - Ensure database exists

3. **Email Not Sending**
   - Check SMTP settings in `.env`
   - Verify Gmail App Password
   - Check firewall settings

### Useful Commands:
```bash
# Clear all caches
php artisan optimize:clear

# Check status
php artisan about

# Generate new key
php artisan key:generate
```

---

## 📞 Support

If you encounter issues:

1. Check `storage/logs/laravel.log` for errors
2. Verify all requirements are met
3. Ensure proper file permissions
4. Contact support with error details

**Requirements:**
- PHP 8.1+
- MySQL 8.0+
- Composer
- Web server (Apache/Nginx)

---

## 📚 Additional Documentation

- [DEPLOYMENT.md](DEPLOYMENT.md) - Detailed deployment guide
- [QUICK_DEPLOY.md](QUICK_DEPLOY.md) - Quick deployment steps
- [DEPLOY_SIMPLE.md](DEPLOY_SIMPLE.md) - Simple deployment guide
- [README.md](README.md) - Main project documentation
- [GUIDE.md](GUIDE.md) - User guide per role
- [API_DOCUMENTATION.md](API_DOCUMENTATION.md) - API documentation 