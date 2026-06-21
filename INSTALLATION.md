# Installation Guide - SIPENA AI

## Prerequisites
- PHP 8.3 or higher
- MySQL 8.0 or higher
- Composer
- Node.js 18+
- Redis (recommended)
- Git

## Local Development Setup

### 1. Clone Repository
```bash
git clone https://github.com/jokodarmono15-cloud/sipena-ai.git
cd sipena-ai
```

### 2. Install PHP Dependencies
```bash
composer install
```

### 3. Install Node Dependencies
```bash
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file dengan konfigurasi Anda:
```env
APP_NAME="SIPENA AI"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipena_ai
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Create Database
```bash
mysql -u root
CREATE DATABASE sipena_ai;
EXIT;
```

### 6. Run Migrations & Seeders
```bash
php artisan migrate
php artisan db:seed
```

### 7. Build Frontend Assets
```bash
npm run dev    # For development
npm run build  # For production
```

### 8. Start Development Server
```bash
php artisan serve
```

Akses aplikasi di: http://localhost:8000

### Default Credentials
```
Email: admin@smkn5tpi.sch.id
Password: password
Role: Super Admin

Email: kepala@smkn5tpi.sch.id
Password: password
Role: Kepala Sekolah

Email: guru1@smkn5tpi.sch.id
Password: password
Role: Guru
```

## Production Deployment (Hostinger)

### 1. Connect via SSH
```bash
ssh your-username@your-hostinger-ip
```

### 2. Navigate to Web Directory
```bash
cd public_html
```

### 3. Clone Repository
```bash
git clone https://github.com/jokodarmono15-cloud/sipena-ai.git sipena-ai
cd sipena-ai
```

### 4. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

### 5. Configure Environment
```bash
cp .env.example .env
php artisan key:generate
```

Update `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://absensi.smkn5tpi.sch.id

DB_HOST=your-db-host
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password
DB_DATABASE=sipena_ai
```

### 6. Setup Database
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 7. Set Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Configure Web Server

**For Nginx:**
```nginx
server {
    listen 443 ssl http2;
    server_name absensi.smkn5tpi.sch.id;
    root /home/username/public_html/sipena-ai/public;
    
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/key.key;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

server {
    listen 80;
    server_name absensi.smkn5tpi.sch.id;
    return 301 https://$server_name$request_uri;
}
```

### 9. Setup SSL (Let's Encrypt)
```bash
certbot certonly --webroot -w /home/username/public_html/sipena-ai/public \
  -d absensi.smkn5tpi.sch.id
```

### 10. Setup Cron Jobs
Add to crontab:
```bash
* * * * * cd /home/username/public_html/sipena-ai && php artisan schedule:run >> /dev/null 2>&1
```

## Verify Installation

### Check Database Connection
```bash
php artisan migrate --pretend
```

### Check Permissions
```bash
php artisan migrate:status
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Troubleshooting

### MySQL Connection Error
- Verify `DB_HOST`, `DB_USER`, `DB_PASSWORD` in `.env`
- Check MySQL service is running
- Verify database exists

### Permission Denied Errors
```bash
sudo chown -R www-data:www-data /path/to/sipena-ai
sudo chmod -R 775 storage bootstrap/cache
```

### Storage Link Not Working
```bash
php artisan storage:link
```

### Composer Out of Memory
```bash
PHP_MEMORY_LIMIT=-1 composer install
```

## Support
Contact: dev@affishope.com | WhatsApp: 088708330988
