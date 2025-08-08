<?php

namespace App\Filament\Resources;

use Croustibat\FilamentJobsMonitor\Resources\QueueMonitorResource as BaseQueueMonitorResource;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;

class QueueMonitorResource extends BaseQueueMonitorResource
{
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'System';

    protected static ?int $navigationSort = 10;

    /**
     * Check if the current user can access this resource.
     */
    public static function canAccess(): bool
    {
        return Gate::allows('access Queue Monitor');
    }

    /**
     * Check if the current user can view any records.
     */
    public static function canViewAny(): bool
    {
        return Gate::allows('access Queue Monitor');
    }

    /**
     * Check if the current user can view a specific record.
     */
    public static function canView(Model $record): bool
    {
        return Gate::allows('view Job Details');
    }

    /**
     * Check if the current user can create records.
     */
    public static function canCreate(): bool
    {
        return Gate::allows('create Job');
    }

    /**
     * Check if the current user can edit records.
     */
    public static function canEdit(Model $record): bool
    {
        return Gate::allows('update Job');
    }

    /**
     * Check if the current user can delete records.
     */
    public static function canDelete(Model $record): bool
    {
        return Gate::allows('delete Jobs');
    }

    /**
     * Check if the current user can delete any records.
     */
    public static function canDeleteAny(): bool
    {
        return Gate::allows('delete Jobs');
    }

    /**
     * Check if the current user can force delete records.
     */
    public static function canForceDelete(Model $record): bool
    {
        return Gate::allows('force-delete Jobs');
    }

    /**
     * Check if the current user can force delete any records.
     */
    public static function canForceDeleteAny(): bool
    {
        return Gate::allows('force-delete Jobs');
    }

    /**
     * Check if the current user can restore records.
     */
    public static function canRestore(Model $record): bool
    {
        return Gate::allows('retry Jobs');
    }

    /**
     * Check if the current user can restore any records.
     */
    public static function canRestoreAny(): bool
    {
        return Gate::allows('retry Jobs');
    }

    /**
     * Check if the current user can replicate records.
     */
    public static function canReplicate(Model $record): bool
    {
        return Gate::allows('create Job');
    }

    /**
     * Check if the current user can reorder records.
     */
    public static function canReorder(): bool
    {
        return Gate::allows('manage Queue Workers');
    }

    public static function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => Gate::allows('view Job Details')),
                
                Tables\Actions\Action::make('retry')
                    ->label('Retry')
                    ->icon('heroicon-m-arrow-path')
                    ->color('warning')
                    ->action(function (Model $record) {
                        // Custom retry logic here if needed
                        // This would depend on the specific queue monitor implementation
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Model $record) => Gate::allows('retry Jobs') && $record->status === 'failed'),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->action(function (Model $record) {
                        // Custom cancel logic here if needed
                    })
                    ->requiresConfirmation()
                    ->visible(fn (Model $record) => Gate::allows('cancel Jobs') && in_array($record->status, ['pending', 'processing'])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Gate::allows('delete Jobs')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Gate::allows('delete Jobs')),
                        
                    Tables\Actions\BulkAction::make('retry_selected')
                        ->label('Retry Selected')
                        ->icon('heroicon-m-arrow-path')
                        ->color('warning')
                        ->action(function ($records) {
                            // Bulk retry logic
                        })
                        ->requiresConfirmation()
                        ->visible(fn () => Gate::allows('retry Jobs')),

                    Tables\Actions\BulkAction::make('cancel_selected')
                        ->label('Cancel Selected')
                        ->icon('heroicon-m-x-mark')
                        ->color('danger')
                        ->action(function ($records) {
                            // Bulk cancel logic
                        })
                        ->requiresConfirmation()
                        ->visible(fn () => Gate::allows('cancel Jobs')),
                ])
                    ->visible(fn () => Gate::allows('batch Process Jobs')),
            ])
            ->headerActions([
                Tables\Actions\Action::make('clear_failed')
                    ->label('Clear Failed Jobs')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->action(function () {
                        // Clear all failed jobs
                    })
                    ->requiresConfirmation()
                    ->visible(fn () => Gate::allows('clear Queues')),

                Tables\Actions\Action::make('view_statistics')
                    ->label('View Statistics')
                    ->icon('heroicon-m-chart-bar')
                    ->color('info')
                    ->url(fn () => route('filament.admin.resources.queue-monitor.statistics'))
                    ->visible(fn () => Gate::allows('view Queue Statistics')),

                Tables\Actions\Action::make('export')
                    ->label('Export Data')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(function () {
                        // Export queue data
                    })
                    ->visible(fn () => Gate::allows('export Queue Data')),
            ]);
    }

    /**
     * Custom method to get queue statistics.
     */
    public static function getQueueStatistics(): array
    {
        if (!Gate::allows('view Queue Statistics')) {
            return [];
        }

        // Return queue statistics data
        return [
            'total_jobs' => 0,
            'pending_jobs' => 0,
            'processing_jobs' => 0,
            'failed_jobs' => 0,
            'completed_jobs' => 0,
        ];
    }

    /**
     * Get navigation badge (job count).
     */
    public static function getNavigationBadge(): ?string
    {
        if (!Gate::allows('view Queue Statistics')) {
            return null;
        }

        // Return count of failed jobs or null to hide badge
        try {
            $stats = static::getQueueStatistics();
            return $stats['failed_jobs'] > 0 ? (string) $stats['failed_jobs'] : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get navigation badge color.
     */
    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'danger'; // Red badge for failed jobs
    }
}