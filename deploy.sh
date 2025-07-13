#!/bin/bash

# E-Prescription System Deployment Script
# Usage: ./deploy.sh [environment]
# Environments: local, shared, vps, docker, heroku

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if environment is provided
if [ -z "$1" ]; then
    echo "Usage: ./deploy.sh [environment]"
    echo "Environments: local, shared, vps, docker, heroku"
    exit 1
fi

ENVIRONMENT=$1

print_status "Starting deployment for environment: $ENVIRONMENT"

# Common deployment steps
deploy_common() {
    print_status "Running common deployment steps..."
    
    # Install dependencies
    print_status "Installing Composer dependencies..."
    composer install --no-dev --optimize-autoloader
    
    # Copy environment file
    if [ ! -f .env ]; then
        print_status "Creating .env file..."
        cp .env.example .env
        print_warning "Please configure your .env file before continuing!"
    fi
    
    # Generate application key
    print_status "Generating application key..."
    php artisan key:generate
    
    # Run migrations
    print_status "Running database migrations..."
    php artisan migrate --force
    
    # Seed database
    print_status "Seeding database..."
    php artisan db:seed --force
    
    # Clear and cache configurations
    print_status "Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Set proper permissions
    print_status "Setting file permissions..."
    chmod -R 755 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
    
    print_success "Common deployment steps completed!"
}

# Local deployment
deploy_local() {
    print_status "Deploying to local environment..."
    
    # Install all dependencies (including dev)
    composer install
    
    # Copy environment file
    if [ ! -f .env ]; then
        cp .env.example .env
        print_warning "Please configure your .env file for local development!"
    fi
    
    # Generate key
    php artisan key:generate
    
    # Run migrations
    php artisan migrate
    
    # Seed database
    php artisan db:seed
    
    # Create storage link
    php artisan storage:link
    
    print_success "Local deployment completed!"
    print_status "Run 'php artisan serve' to start the development server"
}

# Shared hosting deployment
deploy_shared() {
    print_status "Deploying to shared hosting..."
    
    deploy_common
    
    print_status "Creating deployment package..."
    
    # Create deployment directory
    mkdir -p deployment
    
    # Copy files to deployment directory
    rsync -av --exclude='.git' --exclude='node_modules' --exclude='deployment' --exclude='tests' --exclude='.env' . deployment/
    
    # Create deployment archive
    tar -czf eprescription-deployment.tar.gz deployment/
    
    print_success "Shared hosting deployment package created: eprescription-deployment.tar.gz"
    print_status "Upload this file to your shared hosting and extract it"
    print_status "Then run the following commands on your server:"
    echo "  php artisan migrate --force"
    echo "  php artisan db:seed --force"
    echo "  php artisan config:cache"
    echo "  php artisan route:cache"
    echo "  php artisan view:cache"
}

# VPS deployment
deploy_vps() {
    print_status "Deploying to VPS..."
    
    print_warning "This script assumes you have SSH access to your VPS"
    print_warning "Please ensure your VPS has the following installed:"
    echo "  - Nginx"
    echo "  - PHP 8.1+"
    echo "  - MySQL"
    echo "  - Composer"
    
    read -p "Do you want to continue? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        exit 1
    fi
    
    deploy_common
    
    print_status "Setting up Nginx configuration..."
    
    # Create Nginx config
    cat > nginx-eprescription.conf << 'EOF'
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
EOF
    
    print_success "VPS deployment completed!"
    print_status "Nginx configuration created: nginx-eprescription.conf"
    print_status "Please copy this file to /etc/nginx/sites-available/ on your VPS"
}

# Docker deployment
deploy_docker() {
    print_status "Deploying with Docker..."
    
    # Create Dockerfile
    cat > Dockerfile << 'EOF'
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
EOF
    
    # Create docker-compose.yml
    cat > docker-compose.yml << 'EOF'
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
    ports:
      - "3306:3306"
    networks:
      - eprescription

networks:
  eprescription:
    driver: bridge
EOF
    
    # Create Nginx configuration directory
    mkdir -p docker/nginx/conf.d
    
    # Create Nginx config for Docker
    cat > docker/nginx/conf.d/app.conf << 'EOF'
server {
    listen 80;
    index index.php index.html;
    error_log  /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
EOF
    
    print_success "Docker deployment files created!"
    print_status "Run the following commands to start the application:"
    echo "  docker-compose up -d"
    echo "  docker-compose exec app php artisan key:generate"
    echo "  docker-compose exec app php artisan migrate --force"
    echo "  docker-compose exec app php artisan db:seed --force"
    echo "  docker-compose exec app php artisan config:cache"
    echo "  docker-compose exec app php artisan route:cache"
    echo "  docker-compose exec app php artisan view:cache"
}

# Heroku deployment
deploy_heroku() {
    print_status "Deploying to Heroku..."
    
    # Check if Heroku CLI is installed
    if ! command -v heroku &> /dev/null; then
        print_error "Heroku CLI is not installed. Please install it first."
        exit 1
    fi
    
    # Check if logged in to Heroku
    if ! heroku auth:whoami &> /dev/null; then
        print_error "Not logged in to Heroku. Please run 'heroku login' first."
        exit 1
    fi
    
    # Create Heroku app if it doesn't exist
    if [ ! -f .heroku ]; then
        print_status "Creating Heroku app..."
        heroku create
    fi
    
    # Add PostgreSQL addon
    print_status "Adding PostgreSQL addon..."
    heroku addons:create heroku-postgresql:hobby-dev
    
    # Set environment variables
    print_status "Setting environment variables..."
    heroku config:set APP_ENV=production
    heroku config:set APP_DEBUG=false
    heroku config:set APP_KEY=$(php artisan key:generate --show)
    
    # Deploy
    print_status "Deploying to Heroku..."
    git add .
    git commit -m "Deploy to Heroku" || true
    git push heroku main
    
    # Run migrations
    print_status "Running migrations..."
    heroku run php artisan migrate --force
    
    # Seed database
    print_status "Seeding database..."
    heroku run php artisan db:seed --force
    
    print_success "Heroku deployment completed!"
    print_status "Your app is available at: $(heroku info -s | grep web_url | cut -d= -f2)"
}

# Main deployment logic
case $ENVIRONMENT in
    "local")
        deploy_local
        ;;
    "shared")
        deploy_shared
        ;;
    "vps")
        deploy_vps
        ;;
    "docker")
        deploy_docker
        ;;
    "heroku")
        deploy_heroku
        ;;
    *)
        print_error "Unknown environment: $ENVIRONMENT"
        echo "Available environments: local, shared, vps, docker, heroku"
        exit 1
        ;;
esac

print_success "Deployment completed successfully!" 