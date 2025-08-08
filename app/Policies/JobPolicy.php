<?php

namespace App\Policies;

use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can view any jobs.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Job');
    }

    /**
     * Determine whether the user can view the job.
     */
    public function view(User $user, $model): bool
    {
        return $user->checkPermissionTo('view Job');
    }

    /**
     * Determine whether the user can create jobs.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Job');
    }

    /**
     * Determine whether the user can update the job.
     */
    public function update(User $user, $model): bool
    {
        return $user->checkPermissionTo('update Job');
    }

    /**
     * Determine whether the user can delete the job.
     */
    public function delete(User $user, $model): bool
    {
        return $user->checkPermissionTo('delete Job');
    }

    /**
     * Determine whether the user can delete any jobs.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Job');
    }

    /**
     * Determine whether the user can restore the job.
     */
    public function restore(User $user, $model): bool
    {
        return $user->checkPermissionTo('restore Job');
    }

    /**
     * Determine whether the user can restore any jobs.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Job');
    }

    /**
     * Determine whether the user can replicate the job.
     */
    public function replicate(User $user, $model): bool
    {
        return $user->checkPermissionTo('replicate Job');
    }

    /**
     * Determine whether the user can reorder jobs.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Job');
    }

    /**
     * Determine whether the user can permanently delete the job.
     */
    public function forceDelete(User $user, $model): bool
    {
        return $user->checkPermissionTo('force-delete Job');
    }

    /**
     * Determine whether the user can permanently delete any jobs.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Job');
    }

    /**
     * Determine whether the user can retry failed jobs.
     */
    public function retry(User $user, $model): bool
    {
        return $user->checkPermissionTo('retry Job');
    }

    /**
     * Determine whether the user can retry any failed jobs.
     */
    public function retryAny(User $user): bool
    {
        return $user->checkPermissionTo('retry-any Job');
    }

    /**
     * Determine whether the user can cancel pending jobs.
     */
    public function cancel(User $user, $model): bool
    {
        return $user->checkPermissionTo('cancel Job');
    }

    /**
     * Determine whether the user can cancel any pending jobs.
     */
    public function cancelAny(User $user): bool
    {
        return $user->checkPermissionTo('cancel-any Job');
    }
}
