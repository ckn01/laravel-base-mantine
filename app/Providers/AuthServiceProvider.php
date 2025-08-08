<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\PostPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\AuthorPolicy;
use App\Policies\ActivityPolicy;
use App\Policies\PagePolicy;
use App\Policies\JobPolicy;
use App\Policies\QueueMonitorPolicy;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Stephenjude\FilamentBlog\Models\Post;
use Stephenjude\FilamentBlog\Models\Category;
use Stephenjude\FilamentBlog\Models\Author;
use Spatie\Activitylog\Models\Activity;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Post::class => PostPolicy::class,
        Category::class => CategoryPolicy::class,
        Author::class => AuthorPolicy::class,
        Activity::class => ActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for Filament Pages and Settings
        Gate::define('view Settings', function (User $user) {
            return $user->checkPermissionTo('view Settings');
        });

        Gate::define('manage Footer', function (User $user) {
            return $user->checkPermissionTo('manage Footer');
        });

        Gate::define('view System', function (User $user) {
            return $user->checkPermissionTo('view System');
        });

        Gate::define('view Health', function (User $user) {
            return $user->checkPermissionTo('view Health');
        });

        Gate::define('manage Backup', function (User $user) {
            return $user->checkPermissionTo('manage Backup');
        });

        Gate::define('view Activity Log', function (User $user) {
            return $user->checkPermissionTo('view Activity Log');
        });

        // Queue Monitor Gates
        Gate::define('access Queue Monitor', function (User $user) {
            return $user->checkPermissionTo('access Queue Monitor');
        });

        Gate::define('view Queue Statistics', function (User $user) {
            return $user->checkPermissionTo('view Queue Statistics');
        });

        Gate::define('view Queue Performance', function (User $user) {
            return $user->checkPermissionTo('view Queue Performance');
        });

        Gate::define('view Job Details', function (User $user) {
            return $user->checkPermissionTo('view Job Details');
        });

        Gate::define('view Job Payload', function (User $user) {
            return $user->checkPermissionTo('view Job Payload');
        });

        Gate::define('retry Jobs', function (User $user) {
            return $user->checkPermissionTo('retry Jobs');
        });

        Gate::define('delete Jobs', function (User $user) {
            return $user->checkPermissionTo('delete Jobs');
        });

        Gate::define('clear Queues', function (User $user) {
            return $user->checkPermissionTo('clear Queues');
        });

        Gate::define('manage Queue Workers', function (User $user) {
            return $user->checkPermissionTo('manage Queue Workers');
        });

        Gate::define('control Queues', function (User $user) {
            return $user->checkPermissionTo('control Queues');
        });

        Gate::define('prune Jobs', function (User $user) {
            return $user->checkPermissionTo('prune Jobs');
        });

        Gate::define('export Queue Data', function (User $user) {
            return $user->checkPermissionTo('export Queue Data');
        });

        Gate::define('view Queue Configuration', function (User $user) {
            return $user->checkPermissionTo('view Queue Configuration');
        });

        Gate::define('update Queue Configuration', function (User $user) {
            return $user->checkPermissionTo('update Queue Configuration');
        });

        Gate::define('view Queue Logs', function (User $user) {
            return $user->checkPermissionTo('view Queue Logs');
        });

        Gate::define('view Failed Job Details', function (User $user) {
            return $user->checkPermissionTo('view Failed Job Details');
        });

        Gate::define('batch Process Jobs', function (User $user) {
            return $user->checkPermissionTo('batch Process Jobs');
        });

        Gate::define('force-delete Jobs', function (User $user) {
            return $user->checkPermissionTo('force-delete Jobs');
        });

        Gate::define('cancel Jobs', function (User $user) {
            return $user->checkPermissionTo('cancel Jobs');
        });

        Gate::define('view Sensitive Job Data', function (User $user) {
            return $user->checkPermissionTo('view Sensitive Job Data');
        });

        // Super Admin bypass for all permissions
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}