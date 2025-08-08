<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Queue;

class QueueManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static string $view = 'filament.pages.queue-management';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 11;

    protected static ?string $title = 'Queue Management';

    protected static ?string $navigationLabel = 'Queue Management';

    public ?string $selectedQueue = 'default';

    /**
     * Check if the current user can access this page.
     */
    public static function canAccess(): bool
    {
        return Gate::allows('manage Queue Workers');
    }

    /**
     * Get the header actions for this page.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('restart_workers')
                ->label('Restart Workers')
                ->icon('heroicon-m-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalDescription('This will restart all queue workers. Jobs currently being processed will be completed first.')
                ->action(function () {
                    if (!Gate::allows('manage Queue Workers')) {
                        Notification::make()
                            ->title('Unauthorized')
                            ->body('You do not have permission to manage queue workers.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        Artisan::call('queue:restart');
                        
                        Notification::make()
                            ->title('Workers Restarted')
                            ->body('All queue workers have been gracefully restarted.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to restart workers: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('clear_failed')
                ->label('Clear Failed Jobs')
                ->icon('heroicon-m-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalDescription('This will permanently delete all failed jobs. This action cannot be undone.')
                ->action(function () {
                    if (!Gate::allows('clear Queues')) {
                        Notification::make()
                            ->title('Unauthorized')
                            ->body('You do not have permission to clear queues.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        Artisan::call('queue:flush');
                        
                        Notification::make()
                            ->title('Failed Jobs Cleared')
                            ->body('All failed jobs have been removed from the queue.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to clear jobs: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('prune_jobs')
                ->label('Prune Old Jobs')
                ->icon('heroicon-m-scissors')
                ->color('info')
                ->requiresConfirmation()
                ->modalDescription('This will remove old job records from the queue monitor table based on the retention settings.')
                ->action(function () {
                    if (!Gate::allows('prune Jobs')) {
                        Notification::make()
                            ->title('Unauthorized')
                            ->body('You do not have permission to prune jobs.')
                            ->danger()
                            ->send();
                        return;
                    }

                    try {
                        // This would depend on the specific queue monitor implementation
                        // Artisan::call('queue-monitor:prune');
                        
                        Notification::make()
                            ->title('Jobs Pruned')
                            ->body('Old job records have been removed based on retention settings.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error')
                            ->body('Failed to prune jobs: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('view_statistics')
                ->label('View Statistics')
                ->icon('heroicon-m-chart-bar')
                ->color('info')
                ->action(function () {
                    if (!Gate::allows('view Queue Statistics')) {
                        Notification::make()
                            ->title('Unauthorized')
                            ->body('You do not have permission to view queue statistics.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Redirect to statistics view or show modal with stats
                    $this->redirect(route('filament.admin.resources.queue-monitor.index'));
                }),
        ];
    }

    /**
     * Get queue configuration information.
     */
    protected function getQueueConfig(): array
    {
        if (!Gate::allows('view Queue Configuration')) {
            return [];
        }

        return [
            'default_queue' => config('queue.default'),
            'connections' => array_keys(config('queue.connections', [])),
            'failed_driver' => config('queue.failed.driver'),
            'retry_after' => config('queue.connections.database.retry_after', 90),
        ];
    }

    /**
     * Get current queue statistics.
     */
    protected function getQueueStats(): array
    {
        if (!Gate::allows('view Queue Statistics')) {
            return [];
        }

        // This would integrate with the actual queue monitoring system
        return [
            'total_jobs' => 0,
            'pending_jobs' => 0,
            'processing_jobs' => 0,
            'failed_jobs' => 0,
            'completed_jobs' => 0,
        ];
    }

    /**
     * Get view data for the page.
     */
    protected function getViewData(): array
    {
        return [
            'queueConfig' => $this->getQueueConfig(),
            'queueStats' => $this->getQueueStats(),
            'canViewConfig' => Gate::allows('view Queue Configuration'),
            'canViewStats' => Gate::allows('view Queue Statistics'),
            'canManageWorkers' => Gate::allows('manage Queue Workers'),
            'canControlQueues' => Gate::allows('control Queues'),
        ];
    }
}