# Deployment Guide - SIPENA AI

## Pre-Deployment Checklist

- [ ] All tests pass
- [ ] Environment variables configured
- [ ] Database migrations tested
- [ ] SSL certificate ready
- [ ] Backup of database
- [ ] Backup of uploads directory
- [ ] API keys configured
- [ ] Email configuration tested

## Deployment Steps

### 1. Prepare Server
```bash
# Update system
sudo apt-get update && sudo apt-get upgrade -y

# Install required packages
sudo apt-get install -y php8.3-fpm php8.3-mysql php8.3-redis \
  php8.3-mbstring php8.3-gd php8.3-json php8.3-curl \
  nginx mysql-server redis-server composer nodejs npm
```

### 2. Setup Application
```bash
cd /var/www/sipena-ai
git pull origin main
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 3. Database Migration
```bash
php artisan migrate --force
php artisan db:seed:RoleAndPermissionSeeder --force
```

### 4. Configure Nginx
```bash
sudo cp nginx.conf /etc/nginx/sites-available/sipena-ai
sudo ln -s /etc/nginx/sites-available/sipena-ai /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 5. Setup SSL
```bash
sudo certbot certonly -d absensi.smkn5tpi.sch.id
sudo systemctl restart nginx
```

### 6. Setup Queue Worker (if needed)
```bash
sudo cp sipena-ai.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable sipena-ai
sudo systemctl start sipena-ai
```

### 7. Verify Deployment
```bash
curl -I https://absensi.smkn5tpi.sch.id
php artisan health
```

## Post-Deployment

### Monitor Application
```bash
# Check logs
tail -f storage/logs/laravel.log

# Check queue
php artisan queue:failed
```

### Database Backup
```bash
mysqldump -u root -p sipena_ai > backup.sql
```

### Performance Optimization
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

## Rollback Plan

```bash
# Rollback last migration
php artisan migrate:rollback

# Restore from git
git reset --hard HEAD~1

# Restore database
mysql sipena_ai < backup.sql
```

## Monitoring

### System Health
```bash
php artisan health:check
```

### Database
```bash
php artisan db:monitor
```

### Queue
```bash
php artisan queue:monitor
```

## Support
Contact: dev@affishope.com
