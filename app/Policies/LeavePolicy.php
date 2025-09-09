<?php

namespace App\Policies;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LeavePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view leave') || !$user->is_admin;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Leave $leave): bool
    {
        return $user->can('view leave') || 
            ( !$user->is_admin && $leave->member->parent_id == $user->id ) ;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create leave') || !$user->is_admin;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Leave $leave): bool
    {
        return $user->can('edit leave') || ( !$user->is_admin && $leave->member->parent_id == $user->id );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Leave $leave): bool
    {
        return $user->can('delete leave') || ( !$user->is_admin && $leave->member->parent_id == $user->id );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Leave $leave): bool
    {
        return $user->can('delete leave') || ( !$user->is_admin && $leave->member->parent_id == $user->id );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Leave $leave): bool
    {
        return $user->can('force delete leave') || ( !$user->is_admin && $leave->member->parent_id == $user->id );
    }
}
