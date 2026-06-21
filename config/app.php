<?php

return [
    'name' => env('APP_NAME', 'SIPENA AI'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'asset_url' => env('ASSET_URL'),
    'timezone' => 'Asia/Jakarta',
    'locale' => 'id',
    'fallback_locale' => 'en',
    'faker_locale' => 'id_ID',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    // School Configuration
    'school_name' => env('SCHOOL_NAME', 'SMK Negeri 5 Tanjungpinang'),
    'school_domain' => env('SCHOOL_DOMAIN', 'https://smkn5tpi.sch.id'),
    'school_email' => env('SCHOOL_EMAIL', 'info@smkn5tpi.sch.id'),
    'school_phone' => env('SCHOOL_PHONE', '+62123456789'),
    'school_lat' => env('SCHOOL_LAT', -0.9120),
    'school_lng' => env('SCHOOL_LNG', 104.7340),
    
    // Developer Configuration
    'developer_name' => env('DEVELOPER_NAME', 'PT. Affishope Digital Bintan'),
    'developer_website' => env('DEVELOPER_WEBSITE', 'https://digital.affishope.com'),
    'developer_contact' => env('DEVELOPER_CONTACT', 'Joko Darmono, ST'),
    'developer_whatsapp' => env('DEVELOPER_WHATSAPP', '088708330988'),
    'developer_email' => env('DEVELOPER_EMAIL', 'dev@affishope.com'),
    'system_version' => env('SYSTEM_VERSION', '2026'),
];
