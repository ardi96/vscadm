<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BulkInvoice;

class BulkInvoicePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BulkInvoice $bulkInvoice): bool
    {
        return $bulkInvoice->status === 'draft';
    }


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view bulk invoice') || (
            !$user->is_admin
        );
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BulkInvoice $bulkInvoice): bool
    {
        return $user->can('view bulk invoice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create bulk invoice');
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BulkInvoice $bulkInvoice): bool
    {
        return $user->can('delete bulk invoice');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BulkInvoice $bulkInvoice): bool
    {
        return $user->can('delete bulk invoice');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BulkInvoice $bulkInvoice): bool
    {
        return $user->can('delete bulk invoice');
    }



}
