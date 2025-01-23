<?php

namespace App\Policies;

use App\Models\ClassPackage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassPackagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view paket');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassPackage $classPackage): bool
    {
        return $user->can('view paket');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create paket');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassPackage $classPackage): bool
    {
        return $user->can('edit paket');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassPackage $classPackage): bool
    {
        return $user->can('delete paket');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassPackage $classPackage): bool
    {
        return $user->can('delete paket');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassPackage $classPackage): bool
    {
        return $user->can('delete paket');
    }
}
