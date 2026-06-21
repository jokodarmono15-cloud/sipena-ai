<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave;
use Illuminate\Auth\Access\Response;

class LeavePolicy
{
    public function approve(User $user, Leave $leave): bool
    {
        return $user->hasRole(['kepala_sekolah', 'wakil_kepala_sekolah', 'admin']);
    }

    public function view(User $user, Leave $leave): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isHeadmaster()) {
            return true;
        }

        return $user->id === $leave->user_id;
    }
}
