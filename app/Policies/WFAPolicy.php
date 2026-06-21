<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WFA;
use Illuminate\Auth\Access\Response;

class WFAPolicy
{
    public function approve(User $user, WFA $wfa): bool
    {
        return $user->hasRole(['kepala_sekolah', 'wakil_kepala_sekolah', 'admin']);
    }

    public function view(User $user, WFA $wfa): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isHeadmaster()) {
            return true;
        }

        return $user->id === $wfa->user_id;
    }
}
