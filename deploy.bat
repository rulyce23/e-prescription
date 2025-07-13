@echo off
REM E-Prescription System Deployment Script for Windows
REM Usage: deploy.bat [environment]
REM Environments: local, shared, vps, docker, heroku

setlocal enabledelayedexpansion

echo ========================================
echo E-Prescription System Deployment Script
echo ========================================

REM Check if environment is provided
if "%1"=="" (
    echo Usage: deploy.bat [environment]
    echo.
    echo Available environments:
    echo   local    - Local development setup
    echo   shared   - Shared hosting deployment
    echo   vps      - VPS/Cloud server setup
    echo   docker   - Docker containerization
    echo   heroku   - Heroku cloud deployment
    echo.
    pause
    exit /b 1
)

set ENVIRONMENT=%1

echo Starting deployment for environment: %ENVIRONMENT%
echo.

REM Local deployment
if "%ENVIRONMENT%"=="local" goto deploy_local
REM Shared hosting deployment
if "%ENVIRONMENT%"=="shared" goto deploy_shared
REM VPS deployment
if "%ENVIRONMENT%"=="vps" goto deploy_vps
REM Docker deployment
if "%ENVIRONMENT%"=="docker" goto deploy_docker
REM Heroku deployment
if "%ENVIRONMENT%"=="heroku" goto deploy_heroku

echo [ERROR] Unknown environment: %ENVIRONMENT%
echo Available environments: local, shared, vps, docker, heroku
pause
exit /b 1

:deploy_local
echo [INFO] Deploying to local environment...
echo.

echo [INFO] Installing Composer dependencies...
composer install

if not exist .env (
    echo [INFO] Creating .env file...
    copy .env.example .env
    echo [WARNING] Please configure your .env file for local development!
    echo [INFO] Make sure to set your database credentials in .env file
)

echo [INFO] Generating application key...
php artisan key:generate

echo [INFO] Running database migrations...
php artisan migrate

echo [INFO] Seeding database...
php artisan db:seed

echo [INFO] Creating storage link...
php artisan storage:link

echo.
echo [SUCCESS] Local deployment completed!
echo [INFO] Run 'php artisan serve' to start the development server
echo [INFO] Access: http://localhost:8000
echo.
echo Default users:
echo - Admin: admin@eprescription.com / password
echo - Dokter: dokter@eprescription.com / password
echo - Apoteker: apoteker@eprescription.com / password
echo - Pasien: pasien@eprescription.com / password
echo.
pause
goto :eof

:deploy_shared
echo [INFO] Deploying to shared hosting...
echo.

echo [INFO] Installing production dependencies...
composer install --no-dev --optimize-autoloader

echo [INFO] Creating deployment package...
if not exist deployment mkdir deployment

echo [INFO] Copying files to deployment directory...
xcopy /E /I /Y /EXCLUDE:deploy-exclude.txt . deployment\

echo [INFO] Creating deployment archive...
powershell Compress-Archive -Path deployment\* -DestinationPath eprescription-deployment.zip -Force

echo.
echo [SUCCESS] Shared hosting deployment package created!
echo [INFO] File: eprescription-deployment.zip
echo.
echo [INFO] Next steps:
echo 1. Upload eprescription-deployment.zip to your shared hosting
echo 2. Extract the file in public_html or www folder
echo 3. Create MySQL database in cPanel
echo 4. Edit .env file with your database credentials
echo 5. Run these commands via SSH or cPanel Terminal:
echo    php artisan migrate --force
echo    php artisan db:seed --force
echo    php artisan config:cache
echo    php artisan route:cache
echo    php artisan view:cache
echo.
pause
goto :eof

:deploy_vps
echo [INFO] Deploying to VPS...
echo.

echo [WARNING] This script will generate configuration files for VPS deployment
echo [WARNING] Please ensure your VPS has the following installed:
echo   - Nginx
echo   - PHP 8.1+
echo   - MySQL
echo   - Composer
echo.

set /p CONTINUE="Do you want to continue? (y/N): "
if /i not "%CONTINUE%"=="y" goto :eof

echo [INFO] Installing production dependencies...
composer install --no-dev --optimize-autoloader

echo [INFO] Creating Nginx configuration...
echo server { > nginx-eprescription.conf
echo     listen 80; >> nginx-eprescription.conf
echo     server_name yourdomain.com www.yourdomain.com; >> nginx-eprescription.conf
echo     root /var/www/eprescription/public; >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     add_header X-Frame-Options "SAMEORIGIN"; >> nginx-eprescription.conf
echo     add_header X-Content-Type-Options "nosniff"; >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     index index.php; >> nginx-eprescription.conf
echo     charset utf-8; >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     location / { >> nginx-eprescription.conf
echo         try_files $uri $uri/ /index.php?$query_string; >> nginx-eprescription.conf
echo     } >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     location = /favicon.ico { access_log off; log_not_found off; } >> nginx-eprescription.conf
echo     location = /robots.txt  { access_log off; log_not_found off; } >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     error_page 404 /index.php; >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     location ~ \.php$ { >> nginx-eprescription.conf
echo         fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; >> nginx-eprescription.conf
echo         fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name; >> nginx-eprescription.conf
echo         include fastcgi_params; >> nginx-eprescription.conf
echo     } >> nginx-eprescription.conf
echo. >> nginx-eprescription.conf
echo     location ~ /\.(?!well-known).* { >> nginx-eprescription.conf
echo         deny all; >> nginx-eprescription.conf
echo     } >> nginx-eprescription.conf
echo } >> nginx-eprescription.conf

echo [INFO] Creating deployment script...
echo #!/bin/bash > deploy-vps.sh
echo echo "Deploying E-Prescription to VPS..." >> deploy-vps.sh
echo cd /var/www >> deploy-vps.sh
echo sudo git clone https://github.com/your-repo/eprescription.git >> deploy-vps.sh
echo sudo chown -R www-data:www-data eprescription >> deploy-vps.sh
echo cd eprescription >> deploy-vps.sh
echo composer install --no-dev --optimize-autoloader >> deploy-vps.sh
echo cp .env.example .env >> deploy-vps.sh
echo php artisan key:generate >> deploy-vps.sh
echo php artisan migrate --force >> deploy-vps.sh
echo php artisan db:seed --force >> deploy-vps.sh
echo php artisan config:cache >> deploy-vps.sh
echo php artisan route:cache >> deploy-vps.sh
echo php artisan view:cache >> deploy-vps.sh

echo.
echo [SUCCESS] VPS deployment files created!
echo [INFO] Files created:
echo   - nginx-eprescription.conf (Nginx configuration)
echo   - deploy-vps.sh (Deployment script)
echo.
echo [INFO] Next steps:
echo 1. Copy nginx-eprescription.conf to /etc/nginx/sites-available/ on your VPS
echo 2. Run: sudo ln -s /etc/nginx/sites-available/eprescription /etc/nginx/sites-enabled/
echo 3. Run: sudo nginx -t
echo 4. Run: sudo systemctl restart nginx
echo 5. Upload your code to /var/www/eprescription/
echo 6. Run the deploy-vps.sh script
echo.
pause
goto :eof

:deploy_docker
echo [INFO] Deploying with Docker...
echo.

echo [INFO] Creating Dockerfile...
echo FROM php:8.1-fpm > Dockerfile
echo. >> Dockerfile
echo # Install system dependencies >> Dockerfile
echo RUN apt-get update ^&^& apt-get install -y \ >> Dockerfile
echo     git \ >> Dockerfile
echo     curl \ >> Dockerfile
echo     libpng-dev \ >> Dockerfile
echo     libonig-dev \ >> Dockerfile
echo     libxml2-dev \ >> Dockerfile
echo     zip \ >> Dockerfile
echo     unzip >> Dockerfile
echo. >> Dockerfile
echo # Clear cache >> Dockerfile
echo RUN apt-get clean ^&^& rm -rf /var/lib/apt/lists/* >> Dockerfile
echo. >> Dockerfile
echo # Install PHP extensions >> Dockerfile
echo RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd >> Dockerfile
echo. >> Dockerfile
echo # Get latest Composer >> Dockerfile
echo COPY --from=composer:latest /usr/bin/composer /usr/bin/composer >> Dockerfile
echo. >> Dockerfile
echo # Set working directory >> Dockerfile
echo WORKDIR /var/www >> Dockerfile
echo. >> Dockerfile
echo # Copy existing application directory contents >> Dockerfile
echo COPY . /var/www >> Dockerfile
echo. >> Dockerfile
echo # Copy existing application directory permissions >> Dockerfile
echo COPY --chown=www-data:www-data . /var/www >> Dockerfile
echo. >> Dockerfile
echo # Change current user to www >> Dockerfile
echo USER www-data >> Dockerfile
echo. >> Dockerfile
echo # Install dependencies >> Dockerfile
echo RUN composer install --no-dev --optimize-autoloader >> Dockerfile
echo. >> Dockerfile
echo # Expose port 9000 and start php-fpm server >> Dockerfile
echo EXPOSE 9000 >> Dockerfile
echo CMD ["php-fpm"] >> Dockerfile

echo [INFO] Creating docker-compose.yml...
echo version: '3' > docker-compose.yml
echo services: >> docker-compose.yml
echo   app: >> docker-compose.yml
echo     build: >> docker-compose.yml
echo       context: . >> docker-compose.yml
echo       dockerfile: Dockerfile >> docker-compose.yml
echo     container_name: eprescription_app >> docker-compose.yml
echo     restart: unless-stopped >> docker-compose.yml
echo     working_dir: /var/www >> docker-compose.yml
echo     volumes: >> docker-compose.yml
echo       - ./:/var/www >> docker-compose.yml
echo     networks: >> docker-compose.yml
echo       - eprescription >> docker-compose.yml
echo. >> docker-compose.yml
echo   webserver: >> docker-compose.yml
echo     image: nginx:alpine >> docker-compose.yml
echo     container_name: eprescription_nginx >> docker-compose.yml
echo     restart: unless-stopped >> docker-compose.yml
echo     ports: >> docker-compose.yml
echo       - "8000:80" >> docker-compose.yml
echo     volumes: >> docker-compose.yml
echo       - ./:/var/www >> docker-compose.yml
echo       - ./docker/nginx/conf.d/:/etc/nginx/conf.d/ >> docker-compose.yml
echo     networks: >> docker-compose.yml
echo       - eprescription >> docker-compose.yml
echo. >> docker-compose.yml
echo   db: >> docker-compose.yml
echo     image: mysql:8.0 >> docker-compose.yml
echo     container_name: eprescription_db >> docker-compose.yml
echo     restart: unless-stopped >> docker-compose.yml
echo     environment: >> docker-compose.yml
echo       MYSQL_DATABASE: eprescription >> docker-compose.yml
echo       MYSQL_ROOT_PASSWORD: your_mysql_root_password >> docker-compose.yml
echo       MYSQL_PASSWORD: your_mysql_password >> docker-compose.yml
echo       MYSQL_USER: eprescription >> docker-compose.yml
echo     ports: >> docker-compose.yml
echo       - "3306:3306" >> docker-compose.yml
echo     networks: >> docker-compose.yml
echo       - eprescription >> docker-compose.yml
echo. >> docker-compose.yml
echo networks: >> docker-compose.yml
echo   eprescription: >> docker-compose.yml
echo     driver: bridge >> docker-compose.yml

echo [INFO] Creating Nginx configuration for Docker...
if not exist docker\nginx\conf.d mkdir docker\nginx\conf.d
echo server { > docker\nginx\conf.d\app.conf
echo     listen 80; >> docker\nginx\conf.d\app.conf
echo     index index.php index.html; >> docker\nginx\conf.d\app.conf
echo     error_log  /var/log/nginx/error.log; >> docker\nginx\conf.d\app.conf
echo     access_log /var/log/nginx/access.log; >> docker\nginx\conf.d\app.conf
echo     root /var/www/public; >> docker\nginx\conf.d\app.conf
echo. >> docker\nginx\conf.d\app.conf
echo     location ~ \.php$ { >> docker\nginx\conf.d\app.conf
echo         try_files $uri =404; >> docker\nginx\conf.d\app.conf
echo         fastcgi_split_path_info ^(.+\.php)(/.+)$; >> docker\nginx\conf.d\app.conf
echo         fastcgi_pass app:9000; >> docker\nginx\conf.d\app.conf
echo         fastcgi_index index.php; >> docker\nginx\conf.d\app.conf
echo         include fastcgi_params; >> docker\nginx\conf.d\app.conf
echo         fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; >> docker\nginx\conf.d\app.conf
echo         fastcgi_param PATH_INFO $fastcgi_path_info; >> docker\nginx\conf.d\app.conf
echo     } >> docker\nginx\conf.d\app.conf
echo. >> docker\nginx\conf.d\app.conf
echo     location / { >> docker\nginx\conf.d\app.conf
echo         try_files $uri $uri/ /index.php?$query_string; >> docker\nginx\conf.d\app.conf
echo         gzip_static on; >> docker\nginx\conf.d\app.conf
echo     } >> docker\nginx\conf.d\app.conf
echo } >> docker\nginx\conf.d\app.conf

echo.
echo [SUCCESS] Docker deployment files created!
echo [INFO] Files created:
echo   - Dockerfile
echo   - docker-compose.yml
echo   - docker/nginx/conf.d/app.conf
echo.
echo [INFO] Next steps:
echo 1. Edit docker-compose.yml with secure database passwords
echo 2. Run: docker-compose up -d
echo 3. Run: docker-compose exec app php artisan key:generate
echo 4. Run: docker-compose exec app php artisan migrate --force
echo 5. Run: docker-compose exec app php artisan db:seed --force
echo 6. Run: docker-compose exec app php artisan config:cache
echo 7. Run: docker-compose exec app php artisan route:cache
echo 8. Run: docker-compose exec app php artisan view:cache
echo.
echo [INFO] Access: http://localhost:8000
echo.
pause
goto :eof

:deploy_heroku
echo [INFO] Deploying to Heroku...
echo.

echo [WARNING] Please ensure you have Heroku CLI installed and are logged in
echo [WARNING] Run 'heroku login' if you haven't already
echo.

set /p CONTINUE="Do you want to continue? (y/N): "
if /i not "%CONTINUE%"=="y" goto :eof

echo [INFO] Installing production dependencies...
composer install --no-dev --optimize-autoloader

echo [INFO] Creating Heroku app...
heroku create

echo [INFO] Adding PostgreSQL addon...
heroku addons:create heroku-postgresql:hobby-dev

echo [INFO] Setting environment variables...
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
for /f "tokens=*" %%i in ('php artisan key:generate --show') do set APP_KEY=%%i
heroku config:set APP_KEY=%APP_KEY%

echo [INFO] Deploying to Heroku...
git add .
git commit -m "Deploy to Heroku" 2>nul || echo [WARNING] No changes to commit
git push heroku main

echo [INFO] Running migrations...
heroku run php artisan migrate --force

echo [INFO] Seeding database...
heroku run php artisan db:seed --force

echo.
echo [SUCCESS] Heroku deployment completed!
echo [INFO] Your app is available at:
heroku info -s | findstr web_url
echo.
pause
goto :eof 