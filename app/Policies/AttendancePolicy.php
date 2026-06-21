<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Auth\Access\Response;

class AttendancePolicy
{
    public function approve(User $user, Attendance $attendance): bool
    {
        return $user->hasRole(['kepala_sekolah', 'wakil_kepala_sekolah', 'admin']);
    }

    public function view(User $user, Attendance $attendance): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isHeadmaster()) {
            return true;
        }

        return $user->id === $attendance->user_id;
    }
}
