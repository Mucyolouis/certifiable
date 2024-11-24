<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferRequestPredictionPolicy
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