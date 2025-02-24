<?php

namespace App\Policies;

use App\Models\GeneralInfo;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GeneralInfoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view general info') || (
            !$user->is_admin
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GeneralInfo $generalInfo): bool
    {
        return $user->can('view general info') || (
            !$user->is_admin
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create general info');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GeneralInfo $generalInfo): bool
    {
        return $user->can('edit general info');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GeneralInfo $generalInfo): bool
    {
        return $user->can('delete general info');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GeneralInfo $generalInfo): bool
    {
        return $user->can('delete general info');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GeneralInfo $generalInfo): bool
    {
        return $user->can('delete general info');
    }
}
