<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy for Filament Pages and Settings
 */
class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can access the Settings pages.
     */
    public function viewSettings(User $user): bool
    {
        return $user->checkPermissionTo('view Settings');
    }

    /**
     * Determine whether the user can manage footer settings.
     */
    public function manageFooter(User $user): bool
    {
        return $user->checkPermissionTo('manage Footer');
    }

    /**
     * Determine whether the user can access system information.
     */
    public function viewSystem(User $user): bool
    {
        return $user->checkPermissionTo('view System');
    }

    /**
     * Determine whether the user can access health check.
     */
    public function viewHealth(User $user): bool
    {
        return $user->checkPermissionTo('view Health');
    }

    /**
     * Determine whether the user can manage backups.
     */
    public function manageBackups(User $user): bool
    {
        return $user->checkPermissionTo('manage Backup');
    }

    /**
     * Determine whether the user can monitor jobs.
     */
    public function monitorJobs(User $user): bool
    {
        return $user->checkPermissionTo('monitor Jobs');
    }

    /**
     * Determine whether the user can access activity logs.
     */
    public function viewActivityLog(User $user): bool
    {
        return $user->checkPermissionTo('view Activity Log');
    }
}