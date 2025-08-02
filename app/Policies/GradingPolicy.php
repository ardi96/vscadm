<?php

namespace App\Policies;

use App\Models\Grading;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GradingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return ( $user->can('view grading') && ($user->is_admin)) || (
            (!$user->is_admin)
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Grading $grading): bool
    {
        return ( $user->can('view grading') && $user->is_admin ) || (
            (!$user->is_admin) && ($user->id === $grading->member->parent_id && $grading->member->balance <= 0 ) 
            // && $grading->member->balance <= 0
        );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create grading');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Grading $grading): bool
    {
        return $user->can('edit grading');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Grading $grading): bool
    {
        return $user->can('delete grading');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Grading $grading): bool
    {
        return $user->can('delete grading');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Grading $grading): bool
    {
        return $user->can('delete grading');
    }
}
