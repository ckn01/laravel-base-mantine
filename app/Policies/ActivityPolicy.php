<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Activity');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Activity $model): bool
    {
        return $user->checkPermissionTo('view Activity');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Activity logs are typically system-generated
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Activity $model): bool
    {
        // Activity logs should not be editable
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Activity $model): bool
    {
        return $user->checkPermissionTo('delete Activity');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Activity');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Activity $model): bool
    {
        return $user->checkPermissionTo('restore Activity');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Activity');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Activity $model): bool
    {
        return false; // Activity logs should not be replicated
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return false; // Activity logs should not be reordered
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Activity $model): bool
    {
        return $user->checkPermissionTo('force-delete Activity');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Activity');
    }
}