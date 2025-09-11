<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Resignation;

class ResignationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view resignation') || !$user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Resignation $resignation): bool
    {
        return $user->can('view resignation') ||
            (!$user->is_admin && $resignation->member->parent_id == $user->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create resignation') || !$user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Resignation $resignation): bool
    {
        return $user->can('edit resignation') || (!$user->is_admin && $resignation->member->parent_id == $user->id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Resignation $resignation): bool
    {
        return $user->can('delete resignation') || (!$user->is_admin && $resignation->member->parent_id == $user->id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Resignation $resignation): bool
    {
        return $user->can('delete resignation') || (!$user->is_admin && $resignation->member->parent_id == $user->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Resignation $resignation): bool
    {
        return $user->can('force delete resignation') || (!$user->is_admin && $resignation->member->parent_id == $user->id);
    }
}
