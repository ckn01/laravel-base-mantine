<?php

namespace App\Policies;

use App\Models\User;
use Stephenjude\FilamentBlog\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Post');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('view Post');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Post');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $model): bool
    {
        // Allow authors to edit their own posts
        if ($model->blog_author_id && $model->blog_author_id === $user->id) {
            return true;
        }
        
        return $user->checkPermissionTo('update Post');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('delete Post');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Post');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('restore Post');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Post');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('replicate Post');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Post');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('force-delete Post');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Post');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('publish Post');
    }

    /**
     * Determine whether the user can unpublish the model.
     */
    public function unpublish(User $user, Post $model): bool
    {
        return $user->checkPermissionTo('unpublish Post');
    }
}