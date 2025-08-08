<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class TestPermissions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'permissions:test {user?}';

    /**
     * The console command description.
     */
    protected $description = 'Test the permission system for a user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user');
        
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }
        } else {
            $user = User::where('email', 'admin@example.com')->first();
            if (!$user) {
                $this->error('Default admin user not found. Please run: php artisan db:seed --class=PermissionSeeder');
                return 1;
            }
        }

        $this->info("Testing permissions for: {$user->name} ({$user->email})");
        $this->newLine();

        // Test basic info
        $this->info('=== User Information ===');
        $this->line("ID: {$user->id}");
        $this->line("Name: {$user->name}");
        $this->line("Email: {$user->email}");
        $this->newLine();

        // Test roles
        $this->info('=== Roles ===');
        $roles = $user->getRoleNames();
        if ($roles->isEmpty()) {
            $this->warn('No roles assigned');
        } else {
            foreach ($roles as $role) {
                $this->line("✓ {$role}");
            }
        }
        $this->newLine();

        // Test panel access
        $this->info('=== Panel Access ===');
        if ($user->canAccessPanel(\Filament\Facades\Filament::getCurrentPanel())) {
            $this->line('✓ Can access admin panel');
        } else {
            $this->error('✗ Cannot access admin panel');
        }
        $this->newLine();

        // Test key permissions
        $this->info('=== Key Permissions Test ===');
        $testPermissions = [
            'view-any User' => 'View Users',
            'create User' => 'Create Users',
            'update User' => 'Update Users',
            'delete User' => 'Delete Users',
            'view-any Post' => 'View Posts',
            'create Post' => 'Create Posts',
            'publish Post' => 'Publish Posts',
            'view-any Role' => 'View Roles',
            'create Role' => 'Create Roles',
            'manage Footer' => 'Manage Footer Settings',
            'view Activity Log' => 'View Activity Logs',
            'monitor Jobs' => 'Monitor Jobs',
        ];

        foreach ($testPermissions as $permission => $description) {
            if ($user->checkPermissionTo($permission)) {
                $this->line("✓ {$description}");
            } else {
                $this->line("✗ {$description}");
            }
        }
        $this->newLine();

        // Test Gates
        $this->info('=== Gates Test ===');
        $testGates = [
            'view Settings' => 'Access Settings',
            'manage Footer' => 'Manage Footer',
            'view System' => 'View System Info',
            'view Health' => 'View Health Status',
            'manage Backup' => 'Manage Backups',
        ];

        auth()->login($user); // Temporarily login for gate testing
        
        foreach ($testGates as $gate => $description) {
            if (Gate::allows($gate)) {
                $this->line("✓ {$description}");
            } else {
                $this->line("✗ {$description}");
            }
        }
        
        auth()->logout();
        $this->newLine();

        // Test all user permissions
        $this->info('=== All User Permissions ===');
        $allPermissions = $user->getAllPermissions();
        if ($allPermissions->isEmpty()) {
            $this->warn('No permissions assigned');
        } else {
            $this->line("Total permissions: {$allPermissions->count()}");
            if ($this->option('verbose')) {
                foreach ($allPermissions as $permission) {
                    $this->line("  - {$permission->name}");
                }
            } else {
                $this->comment('Use -v flag to see all permissions');
            }
        }
        $this->newLine();

        // System overview
        $this->info('=== System Overview ===');
        $this->line('Total Roles: ' . Role::count());
        $this->line('Total Permissions: ' . Permission::count());
        $this->line('Total Users: ' . User::count());
        
        $this->newLine();
        $this->info('Permission test completed!');
        
        return 0;
    }
}