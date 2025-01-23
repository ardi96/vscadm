<?php

namespace App\Policies;

use App\Models\ClassLocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassLocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view lokasi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassLocation $classLocation): bool
    {
        return $user->can('view lokasi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create lokasi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassLocation $classLocation): bool
    {
        return $user->can('edit lokasi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassLocation $classLocation): bool
    {
        return $user->can('delete lokasi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassLocation $classLocation): bool
    {
        return $user->can('delete lokasi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassLocation $classLocation): bool
    {
        return $user->can('delete lokasi');
    }
}
