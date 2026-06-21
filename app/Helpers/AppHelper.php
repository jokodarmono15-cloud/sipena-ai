<?php

if (!function_exists('logActivity')) {
    function logActivity($userId, $action, $model, $modelId, $changes = [])
    {
        \App\Models\AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('setSetting')) {
    function setSetting($key, $value)
    {
        return \App\Models\Setting::set($key, $value);
    }
}

if (!function_exists('getSchoolInfo')) {
    function getSchoolInfo()
    {
        return [
            'name' => config('app.school_name'),
            'domain' => config('app.school_domain'),
            'email' => config('app.school_email'),
            'phone' => config('app.school_phone'),
        ];
    }
}

if (!function_exists('getDeveloperInfo')) {
    function getDeveloperInfo()
    {
        return [
            'name' => config('app.developer_name'),
            'website' => config('app.developer_website'),
            'contact' => config('app.developer_contact'),
            'whatsapp' => config('app.developer_whatsapp'),
            'email' => config('app.developer_email'),
        ];
    }
}
