<x-filament-panels::page>
    <div class="space-y-6">
        @if($canViewStats)
        <!-- Queue Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $queueStats['total_jobs'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total Jobs</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                        {{ $queueStats['pending_jobs'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Pending</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                        {{ $queueStats['processing_jobs'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Processing</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ $queueStats['failed_jobs'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Failed</div>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ $queueStats['completed_jobs'] ?? 0 }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">Completed</div>
                </div>
            </x-filament::card>
        </div>
        @endif

        @if($canViewConfig)
        <!-- Queue Configuration -->
        <x-filament::card>
            <x-slot name="heading">
                Queue Configuration
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-filament::card>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Default Queue:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $queueConfig['default_queue'] ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Failed Driver:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $queueConfig['failed_driver'] ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Retry After:</span>
                                <span class="text-sm text-gray-900 dark:text-gray-100 font-mono">
                                    {{ $queueConfig['retry_after'] ?? 'N/A' }}s
                                </span>
                            </div>
                        </div>
                    </x-filament::card>
                </div>

                <div>
                    <x-filament::card>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Available Connections:</span>
                                <div class="mt-2 space-y-1">
                                    @foreach($queueConfig['connections'] ?? [] as $connection)
                                        <div class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 dark:bg-gray-700 text-xs font-mono">
                                            {{ $connection }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </x-filament::card>
                </div>
            </div>
        </x-filament::card>
        @endif

        <!-- Queue Management Actions -->
        @if($canManageWorkers || $canControlQueues)
        <x-filament::card>
            <x-slot name="heading">
                Queue Management
            </x-slot>

            <div class="space-y-4">
                <div class="flex flex-wrap gap-4">
                    @if($canManageWorkers)
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Worker Management</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                Manage queue workers and their lifecycle. Use the header actions to restart workers or clear failed jobs.
                            </p>
                        </div>
                    @endif

                    @if($canControlQueues)
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Queue Control</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                                Control queue operations including pausing, resuming, and clearing queues.
                            </p>
                        </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Note:</strong> Queue management operations are performed through the header actions above. 
                        Use caution when performing destructive operations like clearing queues or restarting workers.
                    </div>
                </div>
            </div>
        </x-filament::card>
        @endif

        <!-- Permissions Notice -->
        @if(!$canViewStats && !$canViewConfig && !$canManageWorkers && !$canControlQueues)
        <x-filament::card>
            <div class="text-center py-8">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <h3 class="text-lg font-medium mb-2">Insufficient Permissions</h3>
                    <p class="text-sm">
                        You don't have permission to view queue management information. 
                        Contact your administrator to request access.
                    </p>
                </div>
            </div>
        </x-filament::card>
        @endif
    </div>
</x-filament-panels::page>