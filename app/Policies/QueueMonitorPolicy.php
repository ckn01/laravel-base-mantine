<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class QueueMonitorPolicy
{
    /**
     * Determine whether the user can access the queue monitor.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('access Queue Monitor');
    }

    /**
     * Determine whether the user can view queue statistics.
     */
    public function viewStatistics(User $user): bool
    {
        return $user->checkPermissionTo('view Queue Statistics');
    }

    /**
     * Determine whether the user can view queue performance metrics.
     */
    public function viewPerformance(User $user): bool
    {
        return $user->checkPermissionTo('view Queue Performance');
    }

    /**
     * Determine whether the user can view detailed job information.
     */
    public function viewJobDetails(User $user): bool
    {
        return $user->checkPermissionTo('view Job Details');
    }

    /**
     * Determine whether the user can view job payloads.
     */
    public function viewJobPayload(User $user): bool
    {
        return $user->checkPermissionTo('view Job Payload');
    }

    /**
     * Determine whether the user can retry failed jobs.
     */
    public function retryJobs(User $user): bool
    {
        return $user->checkPermissionTo('retry Jobs');
    }

    /**
     * Determine whether the user can delete jobs from the queue.
     */
    public function deleteJobs(User $user): bool
    {
        return $user->checkPermissionTo('delete Jobs');
    }

    /**
     * Determine whether the user can clear entire queues.
     */
    public function clearQueues(User $user): bool
    {
        return $user->checkPermissionTo('clear Queues');
    }

    /**
     * Determine whether the user can manage queue workers.
     */
    public function manageWorkers(User $user): bool
    {
        return $user->checkPermissionTo('manage Queue Workers');
    }

    /**
     * Determine whether the user can pause/resume queues.
     */
    public function controlQueues(User $user): bool
    {
        return $user->checkPermissionTo('control Queues');
    }

    /**
     * Determine whether the user can prune old job records.
     */
    public function pruneJobs(User $user): bool
    {
        return $user->checkPermissionTo('prune Jobs');
    }

    /**
     * Determine whether the user can export queue data.
     */
    public function exportData(User $user): bool
    {
        return $user->checkPermissionTo('export Queue Data');
    }

    /**
     * Determine whether the user can access queue configuration.
     */
    public function viewConfiguration(User $user): bool
    {
        return $user->checkPermissionTo('view Queue Configuration');
    }

    /**
     * Determine whether the user can modify queue configuration.
     */
    public function updateConfiguration(User $user): bool
    {
        return $user->checkPermissionTo('update Queue Configuration');
    }

    /**
     * Determine whether the user can view queue logs.
     */
    public function viewLogs(User $user): bool
    {
        return $user->checkPermissionTo('view Queue Logs');
    }

    /**
     * Determine whether the user can view failed job stack traces.
     */
    public function viewFailedJobDetails(User $user): bool
    {
        return $user->checkPermissionTo('view Failed Job Details');
    }

    /**
     * Determine whether the user can batch process jobs.
     */
    public function batchProcess(User $user): bool
    {
        return $user->checkPermissionTo('batch Process Jobs');
    }

    /**
     * Determine whether the user can force delete jobs (permanently).
     */
    public function forceDeleteJobs(User $user): bool
    {
        return $user->checkPermissionTo('force-delete Jobs');
    }

    /**
     * Determine whether the user can cancel running jobs.
     */
    public function cancelJobs(User $user): bool
    {
        return $user->checkPermissionTo('cancel Jobs');
    }

    /**
     * Determine whether the user can view sensitive job data.
     */
    public function viewSensitiveData(User $user): bool
    {
        return $user->checkPermissionTo('view Sensitive Job Data');
    }
}