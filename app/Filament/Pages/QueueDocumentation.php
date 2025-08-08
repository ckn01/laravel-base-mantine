<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class QueueDocumentation extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.queue-documentation';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 12;

    protected static ?string $title = 'Queue Monitor Documentation';

    protected static ?string $navigationLabel = 'Queue Docs';

    /**
     * Check if the current user can access this page.
     */
    public static function canAccess(): bool
    {
        return Gate::allows('access Queue Monitor');
    }

    /**
     * Get the queue monitoring permissions structure.
     */
    protected function getPermissionsStructure(): array
    {
        return [
            'Basic Access' => [
                'access Queue Monitor' => 'Required to view the queue monitor interface',
                'view-any Job' => 'View the list of jobs',
                'view Job' => 'View individual job details',
            ],
            'Job Management' => [
                'create Job' => 'Create/dispatch new jobs',
                'update Job' => 'Update job properties',
                'delete Job' => 'Delete individual jobs',
                'delete-any Job' => 'Bulk delete jobs',
                'retry Job' => 'Retry individual failed jobs',
                'retry-any Job' => 'Bulk retry failed jobs',
                'cancel Job' => 'Cancel pending/processing jobs',
                'cancel-any Job' => 'Bulk cancel jobs',
            ],
            'Queue Statistics & Monitoring' => [
                'view Queue Statistics' => 'View queue performance metrics and statistics',
                'view Queue Performance' => 'View detailed performance analytics',
                'view Job Details' => 'View detailed job information including payload',
                'view Job Payload' => 'View sensitive job data and parameters',
                'view Failed Job Details' => 'View error details and stack traces',
                'view Queue Logs' => 'Access queue processing logs',
            ],
            'Queue Management' => [
                'manage Queue Workers' => 'Start, stop, restart queue workers',
                'control Queues' => 'Pause, resume, and control queue processing',
                'clear Queues' => 'Clear entire queues (destructive operation)',
                'prune Jobs' => 'Remove old job records based on retention policy',
                'batch Process Jobs' => 'Perform bulk operations on multiple jobs',
            ],
            'Advanced Operations' => [
                'force-delete Jobs' => 'Permanently delete jobs (cannot be recovered)',
                'view Queue Configuration' => 'View queue system configuration',
                'update Queue Configuration' => 'Modify queue settings',
                'export Queue Data' => 'Export queue data and statistics',
                'view Sensitive Job Data' => 'Access sensitive information in job payloads',
            ],
        ];
    }

    /**
     * Get role-based permission assignments.
     */
    protected function getRolePermissions(): array
    {
        return [
            'Super Admin' => [
                'description' => 'Full access to all queue monitoring and management features',
                'permissions' => 'All permissions',
                'restrictions' => 'None',
            ],
            'Admin' => [
                'description' => 'Comprehensive queue management excluding most destructive operations',
                'permissions' => 'All permissions except: clear Queues, force-delete Jobs, update Queue Configuration, view Sensitive Job Data',
                'restrictions' => 'Cannot perform destructive queue operations or access sensitive job data',
            ],
            'Editor' => [
                'description' => 'Basic queue monitoring with job retry capabilities',
                'permissions' => 'access Queue Monitor, view Queue Statistics, view Job Details, retry Jobs, view-any Job, view Job',
                'restrictions' => 'Cannot delete jobs, manage workers, or access configuration',
            ],
            'Author' => [
                'description' => 'Minimal queue monitoring for own content',
                'permissions' => 'view-any Job, view Job',
                'restrictions' => 'Can only view jobs, no management capabilities',
            ],
        ];
    }

    /**
     * Get security considerations.
     */
    protected function getSecurityConsiderations(): array
    {
        return [
            'Job Payload Access' => [
                'risk' => 'High',
                'description' => 'Job payloads may contain sensitive data like user information, API keys, or business data',
                'mitigation' => 'Restrict "view Job Payload" and "view Sensitive Job Data" permissions to trusted users only',
            ],
            'Queue Configuration' => [
                'risk' => 'High',
                'description' => 'Configuration access can reveal system architecture and connection details',
                'mitigation' => 'Limit "view/update Queue Configuration" to system administrators only',
            ],
            'Destructive Operations' => [
                'risk' => 'Medium',
                'description' => 'Operations like "clear Queues" and "force-delete Jobs" cannot be undone',
                'mitigation' => 'Require confirmation dialogs and restrict to senior administrators',
            ],
            'Worker Management' => [
                'risk' => 'Medium',
                'description' => 'Restarting workers can temporarily disrupt queue processing',
                'mitigation' => 'Limit to users who understand the operational impact',
            ],
        ];
    }

    /**
     * Get usage guidelines.
     */
    protected function getUsageGuidelines(): array
    {
        return [
            'Monitoring Best Practices' => [
                'Regular monitoring of failed jobs and queue performance',
                'Set up notifications for high failure rates or queue backlogs',
                'Review job payloads only when necessary for debugging',
                'Use statistics to identify performance bottlenecks',
            ],
            'Job Management' => [
                'Retry failed jobs in small batches to avoid overwhelming the system',
                'Investigate root cause before retrying recurring failures',
                'Use bulk operations carefully to avoid system overload',
                'Document reasons for manual job interventions',
            ],
            'Worker Management' => [
                'Restart workers during low-traffic periods when possible',
                'Monitor worker processes after restart to ensure proper startup',
                'Use graceful restart to allow current jobs to complete',
                'Keep track of worker configuration changes',
            ],
            'Data Management' => [
                'Regularly prune old job records to maintain performance',
                'Export important queue data before major system changes',
                'Follow data retention policies for job records',
                'Secure any exported queue data appropriately',
            ],
        ];
    }

    /**
     * Get view data for the documentation page.
     */
    protected function getViewData(): array
    {
        return [
            'permissionsStructure' => $this->getPermissionsStructure(),
            'rolePermissions' => $this->getRolePermissions(),
            'securityConsiderations' => $this->getSecurityConsiderations(),
            'usageGuidelines' => $this->getUsageGuidelines(),
        ];
    }
}