# E-Prescription System - Setup Guide

## Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher (XAMPP/WAMP/MAMP)
- Node.js and NPM (for frontend assets)

## Installation Steps

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd e-prescriptions

# Install PHP dependencies
composer install

# Install Node.js dependencies (if using Vite)
npm install
```

### 2. Environment Configuration

Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

Edit the `.env` file with your configuration:

```env
APP_NAME="E-Prescription System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

# Database Configuration (MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eprescription
DB_USERNAME=root
DB_PASSWORD=

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@eprescription.com
MAIL_FROM_NAME="E-Prescription System"
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Database Setup

#### Create MySQL Database

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `eprescription`
3. Set character set to `utf8mb4_unicode_ci`

#### Run Migrations

```bash
php artisan migrate
```

#### Seed Database

```bash
php artisan db:seed
```

### 5. Gmail SMTP Setup

#### Enable 2-Factor Authentication
1. Go to your Google Account settings
2. Enable 2-Factor Authentication

#### Generate App Password
1. Go to Google Account > Security > 2-Step Verification
2. Click "App passwords"
3. Generate a new app password for "Mail"
4. Use this password in your `.env` file

### 6. File Permissions

```bash
# Set proper permissions for storage and cache
chmod -R 775 storage bootstrap/cache
```

### 7. Build Frontend Assets (Optional)

```bash
npm run build
```

### 8. Start Development Server

```bash
php artisan serve
```

Access the application at: http://localhost:8000

## Default Users

After running the seeder, the following users will be created:

| Email | Password | Role |
|-------|----------|------|
| admin@eprescription.com | password | Administrator |
| dokter@eprescription.com | password | Dokter |
| apoteker@eprescription.com | password | Apoteker |

## Features

### User Roles and Permissions

- **Administrator**: Full access to all features
- **Dokter**: Can create, edit, approve, and reject prescriptions
- **Apoteker**: Can view and receive prescriptions

### Core Features

1. **User Authentication**
   - Login with email verification
   - Password reset functionality
   - Role-based access control

2. **Master Data Management**
   - Signa (prescription instructions)
   - Obatalkes (medicines and medical supplies)

3. **Prescription Management**
   - Create prescriptions with non-racikan and racikan items
   - Draft preview functionality
   - Stock validation
   - Approval workflow
   - PDF generation

4. **Dashboard**
   - Statistics based on user role
   - Recent prescriptions
   - Quick actions

## API Endpoints

The system provides comprehensive API endpoints for all functionality:

### Authentication
- `POST /api/login` - User login
- `POST /api/logout` - User logout

### Dashboard
- `GET /api/dashboard/stats` - Get dashboard statistics

### Master Data
- `GET /api/signa` - List signa
- `POST /api/signa` - Create signa
- `GET /api/signa/{id}` - Get signa details
- `PUT /api/signa/{id}` - Update signa
- `DELETE /api/signa/{id}` - Delete signa

### Prescriptions
- `GET /api/resep` - List prescriptions
- `POST /api/resep` - Create prescription
- `GET /api/resep/{id}` - Get prescription details
- `PUT /api/resep/{id}` - Update prescription
- `DELETE /api/resep/{id}` - Delete prescription

### Prescription Actions
- `PATCH /api/resep/{id}/approve` - Approve prescription
- `PATCH /api/resep/{id}/reject` - Reject prescription
- `PATCH /api/resep/{id}/receive` - Receive prescription

## Security Features

- Email verification required for login
- Rate limiting on authentication endpoints
- CSRF protection
- Input validation and sanitization
- Role-based access control
- Secure password reset with tokens

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Ensure MySQL is running
   - Check database credentials in `.env`
   - Verify database exists

2. **Email Not Sending**
   - Check Gmail SMTP settings
   - Ensure app password is correct
   - Verify 2FA is enabled on Gmail

3. **Permission Errors**
   - Set proper file permissions for storage and cache
   - Ensure web server has write access

4. **Migration Errors**
   - Drop and recreate database
   - Run `php artisan migrate:fresh --seed`

### Logs

Check Laravel logs for detailed error information:

```bash
tail -f storage/logs/laravel.log
```

## Support

For technical support or questions, please contact:
- Email: support@eprescription.com
- Documentation: [Link to documentation]

## License

This project is licensed under the MIT License. 