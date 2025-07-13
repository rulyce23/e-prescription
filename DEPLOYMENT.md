# Panduan Deployment E-Prescription System

## Opsi Deployment

### 1. Shared Hosting (cPanel/Web Hosting)
### 2. VPS/Dedicated Server
### 3. Cloud Platforms (Heroku, DigitalOcean, AWS)
### 4. Docker Container

---

## 1. Shared Hosting (cPanel/Web Hosting)

### Persiapan File
```bash
# Buat file .env untuk production
cp .env.example .env
```

### Konfigurasi .env untuk Production
```env
APP_NAME="E-Prescription System"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Langkah-langkah Upload ke Shared Hosting

1. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

2. **Optimize Laravel untuk Production**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

3. **Upload File ke Server**
   - Upload semua file ke folder `public_html` atau `www`
   - Pastikan folder `storage` dan `bootstrap/cache` memiliki permission write

4. **Setup Database**
   - Buat database MySQL di cPanel
   - Import struktur database atau jalankan migration
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Setup Cron Job (Opsional)**
   ```bash
   # Tambahkan ke cPanel Cron Jobs
   * * * * * cd /path/to/your/project && php artisan schedule:run >> /dev/null 2>&1
   ```

---

## 2. VPS/Dedicated Server

### Persiapan Server (Ubuntu 20.04+)

1. **Update System**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

2. **Install Dependencies**
   ```bash
   sudo apt install nginx mysql-server php8.1-fpm php8.1-mysql php8.1-mbstring php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-cli unzip git composer -y
   ```

3. **Setup MySQL**
   ```bash
   sudo mysql_secure_installation
   sudo mysql -u root -p
   CREATE DATABASE eprescription;
   CREATE USER 'eprescription'@'localhost' IDENTIFIED BY 'your_password';
   GRANT ALL PRIVILEGES ON eprescription.* TO 'eprescription'@'localhost';
   FLUSH PRIVILEGES;
   EXIT;
   ```

4. **Setup Nginx**
   ```bash
   sudo nano /etc/nginx/sites-available/eprescription
   ```

   ```nginx
   server {
       listen 80;
       server_name yourdomain.com www.yourdomain.com;
       root /var/www/eprescription/public;

       add_header X-Frame-Options "SAMEORIGIN";
       add_header X-Content-Type-Options "nosniff";

       index index.php;

       charset utf-8;

       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }

       location = /favicon.ico { access_log off; log_not_found off; }
       location = /robots.txt  { access_log off; log_not_found off; }

       error_page 404 /index.php;

       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }

       location ~ /\.(?!well-known).* {
           deny all;
       }
   }
   ```

5. **Enable Site**
   ```bash
   sudo ln -s /etc/nginx/sites-available/eprescription /etc/nginx/sites-enabled/
   sudo nginx -t
   sudo systemctl restart nginx
   ```

6. **Deploy Application**
   ```bash
   cd /var/www
   sudo git clone https://github.com/your-repo/eprescription.git
   sudo chown -R www-data:www-data eprescription
   cd eprescription
   composer install --no-dev --optimize-autoloader
   cp .env.example .env
   # Edit .env file
   php artisan key:generate
   php artisan migrate --force
   php artisan db:seed --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

7. **Setup SSL dengan Let's Encrypt**
   ```bash
   sudo apt install certbot python3-certbot-nginx -y
   sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
   ```

---

## 3. Cloud Platforms

### Heroku

1. **Install Heroku CLI**
   ```bash
   # Download dan install dari https://devcenter.heroku.com/articles/heroku-cli
   ```

2. **Setup Heroku App**
   ```bash
   heroku create your-eprescription-app
   heroku addons:create heroku-postgresql:hobby-dev
   heroku addons:create heroku-redis:hobby-dev
   ```

3. **Configure Environment**
   ```bash
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set APP_KEY=$(php artisan key:generate --show)
   heroku config:set MAIL_MAILER=smtp
   heroku config:set MAIL_HOST=smtp.gmail.com
   heroku config:set MAIL_PORT=587
   heroku config:set MAIL_USERNAME=your-email@gmail.com
   heroku config:set MAIL_PASSWORD=your-app-password
   ```

4. **Deploy**
   ```bash
   git add .
   git commit -m "Deploy to Heroku"
   git push heroku main
   heroku run php artisan migrate --force
   heroku run php artisan db:seed --force
   ```

### DigitalOcean App Platform

1. **Connect Repository**
   - Buka DigitalOcean App Platform
   - Connect GitHub repository
   - Pilih branch main

2. **Configure Environment**
   - Set environment variables
   - Configure database service
   - Set build command: `composer install --no-dev --optimize-autoloader`
   - Set run command: `php artisan serve --host=0.0.0.0 --port=$PORT`

3. **Deploy**
   - Click "Create Resources"
   - Wait for deployment

---

## 4. Docker Deployment

### Dockerfile
```dockerfile
FROM php:8.1-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www

# Change current user to www
USER www-data

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml
```yaml
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: eprescription_app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - eprescription

  webserver:
    image: nginx:alpine
    container_name: eprescription_nginx
    restart: unless-stopped
    ports:
      - "8000:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - eprescription

  db:
    image: mysql:8.0
    container_name: eprescription_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: eprescription
      MYSQL_ROOT_PASSWORD: your_mysql_root_password
      MYSQL_PASSWORD: your_mysql_password
      MYSQL_USER: eprescription
      SERVICE_TAGS: dev
      SERVICE_NAME: mysql
    ports:
      - "3306:3306"
    volumes:
      - ./docker/mysql:/docker-entrypoint-initdb.d
    networks:
      - eprescription

networks:
  eprescription:
    driver: bridge
```

### Deploy dengan Docker
```bash
# Build dan run containers
docker-compose up -d

# Setup application
docker-compose exec app composer install --no-dev --optimize-autoloader
docker-compose exec app cp .env.example .env
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed --force
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

---

## Post-Deployment Checklist

### Security
- [ ] Set `APP_DEBUG=false`
- [ ] Generate strong `APP_KEY`
- [ ] Use HTTPS/SSL
- [ ] Set proper file permissions
- [ ] Configure firewall
- [ ] Update dependencies regularly

### Performance
- [ ] Enable OPcache
- [ ] Configure Redis for caching
- [ ] Optimize database queries
- [ ] Enable compression
- [ ] Setup CDN for assets

### Monitoring
- [ ] Setup error logging
- [ ] Configure backup strategy
- [ ] Monitor server resources
- [ ] Setup uptime monitoring

### Maintenance
- [ ] Setup automated backups
- [ ] Configure log rotation
- [ ] Setup update procedures
- [ ] Document deployment process

---

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   sudo chown -R www-data:www-data /var/www/eprescription
   sudo chmod -R 755 /var/www/eprescription
   sudo chmod -R 775 /var/www/eprescription/storage
   sudo chmod -R 775 /var/www/eprescription/bootstrap/cache
   ```

2. **Database Connection Issues**
   - Check database credentials in `.env`
   - Verify database server is running
   - Check firewall settings

3. **500 Internal Server Error**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Check Nginx/Apache error logs
   - Verify file permissions

4. **Email Not Sending**
   - Check SMTP configuration
   - Verify Gmail App Password
   - Check firewall blocking port 587

### Useful Commands
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check Laravel status
php artisan about

# Generate new application key
php artisan key:generate

# Check storage link
php artisan storage:link
```

---

## Support

Jika mengalami masalah saat deployment, silakan:
1. Periksa log error di `storage/logs/laravel.log`
2. Pastikan semua requirement terpenuhi
3. Verifikasi konfigurasi database dan email
4. Hubungi support dengan detail error yang muncul 