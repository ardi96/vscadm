<?php

namespace App\Policies;

use App\Models\CostumeSize;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CostumeSizePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view costume size');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CostumeSize $costumeSize): bool
    {
        return $user->can('view costume size');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create costume size');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CostumeSize $costumeSize): bool
    {
        return $user->can('edit costume size');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CostumeSize $costumeSize): bool
    {
        return $user->can('delete costume size');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CostumeSize $costumeSize): bool
    {
        return $user->can('delete costume size');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CostumeSize $costumeSize): bool
    {
        return $user->can('deletecostume size');
    }
}
