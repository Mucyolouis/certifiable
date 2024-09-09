<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPredictionDashboardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the dashboard.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        return $user->hasAnyRole(['pastor', 'superadmin']);
    }

    /**
     * Determine whether the user can view any dashboards.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['pastor', 'super_admin']);
    }
}