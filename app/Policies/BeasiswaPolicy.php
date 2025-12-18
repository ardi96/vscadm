<?php

namespace App\Policies;

use App\Models\Beasiswa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BeasiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view beasiswa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Beasiswa $beasiswa): bool
    {
        return $user->can('view beasiswa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create beasiswa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Beasiswa $beasiswa): bool
    {
        return $user->can('edit beasiswa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Beasiswa $beasiswa): bool
    {
        return $user->can('delete beasiswa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Beasiswa $beasiswa): bool
    {
        return $user->can('delete beasiswa');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Beasiswa $beasiswa): bool
    {
        return $user->can('delete beasiswa');
    }
}
