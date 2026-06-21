<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'nip',
        'password',
        'phone',
        'avatar',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function wfas()
    {
        return $this->hasMany(WFA::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function outsideTasks()
    {
        return $this->hasMany(OutsideTask::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function isAdmin()
    {
        return $this->hasRole(['super_admin', 'admin']);
    }

    public function isHeadmaster()
    {
        return $this->hasRole(['kepala_sekolah', 'wakil_kepala_sekolah']);
    }

    public function isTeacher()
    {
        return $this->hasRole('guru');
    }
}
