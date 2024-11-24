<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BaptismPredictionPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasRole(['super_admin', 'pastor']);
    }

    public function view(User $user): bool
    {
        return $user->hasRole(['super_admin', 'pastor']);
    }
}