<?php

namespace App\Policies;

use App\Models\User;
use Stephenjude\FilamentBlog\Models\Author;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Author');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('view Author');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Author');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('update Author');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('delete Author');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Author');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('restore Author');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Author');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('replicate Author');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Author');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Author $model): bool
    {
        return $user->checkPermissionTo('force-delete Author');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Author');
    }
}