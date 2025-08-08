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

        Gate::define('monitor Jobs', function (User $user) {
            return $user->checkPermissionTo('monitor Jobs');
        });

        Gate::define('view Activity Log', function (User $user) {
            return $user->checkPermissionTo('view Activity Log');
        });

        // Super Admin bypass for all permissions
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('Super Admin')) {
                return true;
            }
        });
    }
}