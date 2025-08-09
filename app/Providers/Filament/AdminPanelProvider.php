<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
// use Afsakar\FilamentOtpLogin\FilamentOtpLoginPlugin;
use Stephenjude\FilamentBlog\BlogPlugin;
// use App\Http\Middleware\FilamentAuthorizationMiddleware;
use Filament\Navigation\NavigationItem;
use Illuminate\Support\Facades\Auth;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin-dashboard')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                // Pages\SettingsPage::class,

            ])
            ->resources([
                \App\Filament\Resources\UserResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->plugin(BlogPlugin::make())
            ->plugin(FilamentJobsMonitorPlugin::make()->enableNavigation())
            ->plugin(ActivitylogPlugin::make())
            ->plugin(FilamentSpatieLaravelBackupPlugin::make()->authorize(fn (): bool => Auth::user()->hasRole('super admin')))
            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->plugin(FilamentSpatieLaravelHealthPlugin::make()->authorize(fn (): bool => Auth::user()->hasRole('super admin')))
            ->navigationItems([
                NavigationItem::make('Back to Main App')
                    ->url('/dashboard')
                    ->icon('heroicon-o-arrow-left')
                    // ->group('Navigation')
                    ->sort(1)
                    ->openUrlInNewTab(false), // Paksa buka di tab yang sama untuk refresh
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Content Management')
                    ->icon('heroicon-o-document-text')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('User Management')
                    // ->icon('heroicon-o-users')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('System')
                    // ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Settings')
                    // ->icon('heroicon-o-adjustments-horizontal')
                    ->collapsed(),
            ])
            // ->plugin(FilamentOtpLoginPlugin::make())
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,  
                DispatchServingFilamentEvent::class,
                // FilamentAuthorizationMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
