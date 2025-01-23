<?php

namespace App\Policies;

use App\Models\ClassPackageSchedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassPackageSchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view jadwal');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassPackageSchedule $classPackageSchedule): bool
    {
        return $user->can('view jadwal');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create jadwal');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassPackageSchedule $classPackageSchedule): bool
    {
        return $user->can('edit jadwal');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassPackageSchedule $classPackageSchedule): bool
    {
        return $user->can('delete jadwal');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassPackageSchedule $classPackageSchedule): bool
    {
        return $user->can('delete jadwal');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassPackageSchedule $classPackageSchedule): bool
    {
        return $user->can('delete jadwal');        
    }
}
