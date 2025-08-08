<?php

return [
    'resources' => [
        'enabled' => true,
        'label' => 'Job',
        'plural_label' => 'Jobs',
        'navigation_group' => 'System',
        'navigation_icon' => 'heroicon-o-cpu-chip',
        'navigation_sort' => 10,
        'navigation_count_badge' => true,
        'resource' => App\Filament\Resources\QueueMonitorResource::class,
        'cluster' => null,
    ],
    'pruning' => [
        'enabled' => true,
        'retention_days' => 7,
    ],
    'queues' => [
        'default',
    ],
];
