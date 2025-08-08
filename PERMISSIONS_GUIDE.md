# Filament Admin Panel Permission System Guide

This Laravel application uses Spatie Laravel Permission package to provide comprehensive role-based access control (RBAC) for the Filament admin panel.

## Overview

The permission system includes:
- **Policies** for all Filament resources (User, Post, Category, Author, Role, Permission, Activity)
- **Gates** for Filament pages and system features
- **Middleware** for additional authorization checks
- **Roles** with pre-configured permissions
- **Seeders** for initial setup

## Default Roles

### Super Admin
- **Access:** Everything
- **Description:** Complete system access, bypasses all permission checks
- **Default User:** admin@example.com / password

### Admin
- **Access:** Most features except sensitive system operations
- **Restrictions:** Cannot force delete users, roles, or permissions
- **Use Case:** Day-to-day administration

### Editor
- **Access:** Content management (Posts, Categories, Authors)
- **Restrictions:** Limited user management (view only)
- **Use Case:** Content creators and managers

### Author
- **Access:** Basic content creation
- **Restrictions:** Can only edit their own posts, limited system access
- **Use Case:** Content contributors

## Permission Structure

Permissions follow Filament's standard pattern:

### Resource Permissions
For each model (User, Post, Category, etc.):
- `view-any {Model}` - View resource list
- `view {Model}` - View individual record
- `create {Model}` - Create new records
- `update {Model}` - Edit existing records
- `delete {Model}` - Delete records
- `delete-any {Model}` - Bulk delete
- `restore {Model}` - Restore soft-deleted records
- `restore-any {Model}` - Bulk restore
- `replicate {Model}` - Clone records
- `reorder {Model}` - Change record order
- `force-delete {Model}` - Permanently delete
- `force-delete-any {Model}` - Bulk permanent delete

### Special Post Permissions
- `publish Post` - Publish blog posts
- `unpublish Post` - Unpublish blog posts

### System Permissions
- `view Settings` - Access settings pages
- `manage Footer` - Edit footer settings
- `view System` - View system information
- `view Health` - Access health checks
- `manage Backup` - Manage system backups
- `monitor Jobs` - Monitor job queues
- `view Activity Log` - View activity logs
- `impersonate User` - Impersonate other users

## Authorization Logic

### Policies
Each model has a corresponding policy in `app/Policies/`:
- `UserPolicy` - User management
- `PostPolicy` - Blog posts (includes author ownership check)
- `CategoryPolicy` - Blog categories
- `AuthorPolicy` - Blog authors
- `RolePolicy` - Role management (prevents Super Admin role deletion)
- `PermissionPolicy` - Permission management
- `ActivityPolicy` - Activity logs (read-only)

### Gates
Pages and system features use Gates defined in `AuthServiceProvider`:
```php
Gate::define('manage Footer', function (User $user) {
    return $user->checkPermissionTo('manage Footer');
});
```

### Special Logic

#### Super Admin Bypass
Super Admins automatically pass all authorization checks:
```php
Gate::before(function (User $user, string $ability) {
    if ($user->hasRole('Super Admin')) {
        return true;
    }
});
```

#### Author Post Ownership
Authors can edit their own posts regardless of update permissions:
```php
public function update(User $user, Post $model): bool
{
    if ($model->blog_author_id && $model->blog_author_id === $user->id) {
        return true;
    }
    return $user->checkPermissionTo('update Post');
}
```

#### Role Protection
Prevents modification/deletion of Super Admin role by non-Super Admins:
```php
public function update(User $user, Role $model): bool
{
    if ($model->name === 'Super Admin' && !$user->hasRole('Super Admin')) {
        return false;
    }
    return $user->checkPermissionTo('update Role');
}
```

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Permissions and Roles
```bash
php artisan db:seed --class=PermissionSeeder
```

This creates:
- All permissions
- Default roles with appropriate permissions
- Super Admin user (admin@example.com / password)

### 3. Assign Roles to Users
```php
// In your controller or seeder
$user->assignRole('Admin');
$user->assignRole(['Editor', 'Author']); // Multiple roles

// Or using Filament UI
// Edit user in admin panel and assign roles
```

## Managing Permissions

### Via Filament Admin Panel
1. Login as Super Admin or Admin
2. Navigate to System → Roles or System → Permissions
3. Create/edit roles and assign permissions
4. Navigate to User Management → Users
5. Edit users and assign roles

### Programmatically
```php
// Create permission
Permission::create(['name' => 'custom permission']);

// Create role
$role = Role::create(['name' => 'Custom Role']);

// Assign permissions to role
$role->givePermissionTo('view-any User', 'create User');

// Assign role to user
$user->assignRole('Custom Role');

// Direct permission assignment
$user->givePermissionTo('custom permission');
```

## Extending the System

### Adding New Resource Policies

1. **Create Policy:**
```bash
php artisan make:policy YourModelPolicy
```

2. **Implement Methods:**
```php
public function viewAny(User $user): bool
{
    return $user->checkPermissionTo('view-any YourModel');
}
```

3. **Register Policy:**
```php
// In AuthServiceProvider
protected $policies = [
    YourModel::class => YourModelPolicy::class,
];
```

4. **Add Permissions:**
```php
// In PermissionSeeder or manually
Permission::create(['name' => 'view-any YourModel']);
```

### Adding Page Authorization

1. **Define Gate:**
```php
// In AuthServiceProvider::boot()
Gate::define('access your-page', function (User $user) {
    return $user->checkPermissionTo('access your-page');
});
```

2. **Apply to Page:**
```php
// In your Filament page
public static function canAccess(): bool
{
    return Gate::allows('access your-page');
}
```

### Custom Middleware Authorization

Update `FilamentAuthorizationMiddleware` to handle new routes:
```php
if (str_contains($routeName, 'your-feature')) {
    if (!Gate::allows('access your-feature')) {
        abort(403, 'Unauthorized access.');
    }
}
```

## Security Best Practices

1. **Principle of Least Privilege:** Users should have minimum necessary permissions
2. **Regular Audits:** Review user roles and permissions periodically
3. **Role Segregation:** Separate roles for different responsibilities
4. **Super Admin Protection:** Limit Super Admin role assignment
5. **Activity Logging:** Monitor user actions through activity logs

## Troubleshooting

### Permission Cache Issues
```bash
php artisan permission:cache-reset
```

### Missing Permissions
Run the permission seeder again:
```bash
php artisan db:seed --class=PermissionSeeder
```

### 403 Errors
1. Check user roles: `$user->getRoleNames()`
2. Check permissions: `$user->getAllPermissions()`
3. Verify policy methods are implemented correctly
4. Clear permission cache

### Testing Permissions
```php
// Check if user can perform action
if ($user->can('update', $post)) {
    // User can update the post
}

// Check permission directly
if ($user->checkPermissionTo('view-any User')) {
    // User has permission
}

// Check role
if ($user->hasRole('Admin')) {
    // User is an admin
}
```

## Files Overview

- **Policies:** `app/Policies/`
- **Authorization Service:** `app/Providers/AuthServiceProvider.php`
- **Middleware:** `app/Http/Middleware/FilamentAuthorizationMiddleware.php`
- **Seeders:** `database/seeders/PermissionSeeder.php`
- **User Model:** `app/Models/User.php` (includes `canAccessPanel` method)

This comprehensive permission system ensures secure, role-based access to all Filament admin panel features while remaining flexible for future extensions.