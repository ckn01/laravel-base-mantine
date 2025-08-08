<?php

namespace App\Providers\Filament;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use App\Filament\Resources\UserResource;

class NavigationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Custom navigation configuration can be added here if needed
        // This is an example of how to completely override navigation
        /*
        Filament::serving(function () {
            Filament::navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->icon('heroicon-o-home')
                            ->url(Dashboard::getUrl())
                            ->isActiveWhen(fn () => request()->routeIs('filament.admin.pages.dashboard'))
                            ->sort(1),
                    ])
                    ->groups([
                        NavigationGroup::make('User Management')
                            ->items([
                                ...UserResource::getNavigationItems(),
                            ])
                            ->icon('heroicon-o-users')
                            ->sort(10),
                        
                        NavigationGroup::make('Content')
                            ->items([
                                // Blog plugin items would go here
                            ])
                            ->icon('heroicon-o-document-text')
                            ->sort(20),
                            
                        NavigationGroup::make('System')
                            ->items([
                                // Activity Log, Health Check items would go here
                            ])
                            ->icon('heroicon-o-cog-6-tooth')
                            ->sort(30),
                            
                        NavigationGroup::make('Settings')
                            ->items([
                                // Jobs Monitor, Backup items would go here
                            ])
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->sort(40),
                    ]);
            });
        });
        */
    }
}
