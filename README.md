# SIPENA AI - Sistem Informasi Presensi dan Administrasi Guru Berbasis AI

## 📚 Dokumentasi Lengkap

### Daftar Isi
1. [Instalasi](#instalasi)
2. [Konfigurasi](#konfigurasi)
3. [Struktur Proyek](#struktur-proyek)
4. [Fitur Utama](#fitur-utama)
5. [API Documentation](#api-documentation)
6. [Deployment](#deployment)
7. [Troubleshooting](#troubleshooting)

---

## 🚀 Instalasi

### Requirements
- PHP 8.3+
- MySQL 8.0+
- Composer
- Node.js 18+
- Redis (optional)
- Qdrant Vector DB (untuk AI features)

### Steps

1. **Clone Repository**
   ```bash
   git clone https://github.com/jokodarmono15-cloud/sipena-ai.git
   cd sipena-ai
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

4. **Configure Database**
   ```bash
   # Edit .env
   DB_DATABASE=sipena_ai
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start Server**
   ```bash
   php artisan serve
   ```

---

## ⚙️ Konfigurasi

### Environment Variables

#### Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipena_ai
DB_USERNAME=root
DB_PASSWORD=
```

#### Cache & Queue
```env
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

#### AI Configuration
```env
AI_PROVIDER=openrouter  # openrouter, openai, gemini, groq
AI_API_KEY=your_api_key
AI_MODEL=auto
AI_AUTO_FALLBACK=true
```

#### School Settings
```env
SCHOOL_NAME="SMK Negeri 5 Tanjungpinang"
SCHOOL_LAT=-0.9120
SCHOOL_LNG=104.7340
GEOFENCE_RADIUS=500  # meters
```

#### Vector Database (Qdrant)
```env
VECTOR_DB_TYPE=qdrant
VECTOR_DB_HOST=localhost
VECTOR_DB_PORT=6333
VECTOR_DB_API_KEY=your_qdrant_key
```

---

## 📁 Struktur Proyek

```
sipen-ai/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Business Logic
│   │   ├── Middleware/       # HTTP Middleware
│   │   └── Policies/         # Authorization Policies
│   ├── Models/               # Eloquent Models
│   ├── Services/             # Service Layer
│   └── Helpers/              # Helper Functions
├── database/
│   ├── migrations/           # Database Migrations
│   └── seeders/              # Database Seeders
├── resources/
│   └── views/                # Blade Templates
├── routes/
│   ├── web.php               # Web Routes
│   └── api.php               # API Routes
├── config/
│   ├── app.php               # App Configuration
│   └── services.php          # Services Configuration
└── storage/                  # Uploads & Logs
```

---

## ✨ Fitur Utama

### 1. **Attendance System**
- Check-in/Check-out dengan GPS dan foto
- Geofencing validation (default 500m)
- Riwayat absensi per bulan
- Approval workflow untuk admin

### 2. **Work From Anywhere (WFA)**
- Pengajuan WFA dengan bukti (foto, screenshot, dokumen)
- Tracking lokasi WFA
- Sistem approval otomatis
- History dan reporting

### 3. **Leave Management**
- Tiga tipe izin: Izin, Sakit, Tahunan
- Upload dokumen pendukung
- Surat keterangan dokter (untuk sakit)
- Workflow approval

### 4. **AI Assistant**
- ChatBot berbasis AI
- Semantic search di Knowledge Base
- Multi-provider support (OpenRouter, OpenAI, Gemini, Groq)
- Context-aware responses

### 5. **Knowledge Base**
- SOP & Kebijakan
- Tata Tertib
- Kalender Akademik
- FAQ & Panduan
- Vector-based semantic search

### 6. **Dashboards**
- Teacher: Personal attendance & requests
- Headmaster: School attendance overview & approvals
- Admin: System analytics & audit logs

### 7. **Audit Logging**
- Track semua user actions
- IP address & User Agent logging
- Change history per model

---

## 🔌 API Documentation

### Authentication
```bash
POST /api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

### Attendance
```bash
# Check In
POST /attendance/check-in
{
  "latitude": -0.9120,
  "longitude": 104.7340,
  "photo": "base64_image"
}

# Check Out
POST /attendance/check-out
{
  "latitude": -0.9120,
  "longitude": 104.7340,
  "photo": "base64_image"
}

# Get History
GET /attendance/history
```

### WFA
```bash
# Create WFA
POST /wfa
{
  "date": "2026-06-21",
  "activity": "Mengajar online",
  "latitude": -0.9120,
  "longitude": 104.7340
}

# List WFA
GET /wfa
```

### Chat AI
```bash
POST /chat-internal/send
{
  "message": "Bagaimana cara check-in?"
}
```

---

## 🌐 Deployment ke Hostinger

### 1. **SSH ke Server**
   ```bash
   ssh user@your-hostinger-ip
   ```

### 2. **Install PHP Extensions**
   ```bash
   apt-get install php8.3-mysql php8.3-redis php8.3-mbstring
   ```

### 3. **Clone & Setup**
   ```bash
   cd public_html
   git clone https://github.com/jokodarmono15-cloud/sipena-ai.git
   cd sipena-ai
   composer install --no-dev
   cp .env.production .env
   php artisan migrate --force
   php artisan cache:clear
   ```

### 4. **Configure Web Server (Nginx)**
   ```nginx
   server {
       listen 80;
       server_name absensi.smkn5tpi.sch.id;
       root /home/user/public_html/sipena-ai/public;
       
       index index.php index.html;
       
       location / {
           try_files $uri $uri/ /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/run/php/php8.3-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

### 5. **SSL Certificate (Let's Encrypt)**
   ```bash
   certbot certonly -d absensi.smkn5tpi.sch.id
   ```

### 6. **Setup Queue Worker**
   ```bash
   # Add to crontab
   * * * * * cd /home/user/public_html/sipena-ai && php artisan schedule:run >> /dev/null 2>&1
   ```

---

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Check MySQL is running
sudo systemctl status mysql

# Verify credentials in .env
php artisan migrate --verbose
```

### Permission Denied on Storage
```bash
chmod -R 775 storage/ bootstrap/cache
chown -R www-data:www-data storage/ bootstrap/cache
```

### AI Provider Error
```bash
# Verify API keys in .env
php artisan tinker
>>> config('services.ai.api_key')
```

### Geofencing Not Working
```bash
# Check SCHOOL_LAT and SCHOOL_LNG
php artisan tinker
>>> config('app.school_lat')
>>> config('app.school_lng')
```

---

## 📞 Support & Contact

**Developer:** PT. Affishope Digital Bintan  
**Contact:** Joko Darmono, ST  
**WhatsApp:** 088708330988  
**Email:** dev@affishope.com  
**Website:** https://digital.affishope.com

---

## 📄 License

MIT License - 2026
