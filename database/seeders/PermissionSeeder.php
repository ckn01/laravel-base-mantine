<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $permissions = [
            // User permissions
            'view-any User',
            'view User',
            'create User',
            'update User',
            'delete User',
            'delete-any User',
            'restore User',
            'restore-any User',
            'replicate User',
            'reorder User',
            'force-delete User',
            'force-delete-any User',
            'impersonate User',

            // Role permissions
            'view-any Role',
            'view Role',
            'create Role',
            'update Role',
            'delete Role',
            'delete-any Role',
            'restore Role',
            'restore-any Role',
            'replicate Role',
            'reorder Role',
            'force-delete Role',
            'force-delete-any Role',

            // Permission permissions
            'view-any Permission',
            'view Permission',
            'create Permission',
            'update Permission',
            'delete Permission',
            'delete-any Permission',
            'restore Permission',
            'restore-any Permission',
            'replicate Permission',
            'reorder Permission',
            'force-delete Permission',
            'force-delete-any Permission',

            // Blog Post permissions
            'view-any Post',
            'view Post',
            'create Post',
            'update Post',
            'delete Post',
            'delete-any Post',
            'restore Post',
            'restore-any Post',
            'replicate Post',
            'reorder Post',
            'force-delete Post',
            'force-delete-any Post',
            'publish Post',
            'unpublish Post',

            // Blog Category permissions
            'view-any Category',
            'view Category',
            'create Category',
            'update Category',
            'delete Category',
            'delete-any Category',
            'restore Category',
            'restore-any Category',
            'replicate Category',
            'reorder Category',
            'force-delete Category',
            'force-delete-any Category',

            // Blog Author permissions
            'view-any Author',
            'view Author',
            'create Author',
            'update Author',
            'delete Author',
            'delete-any Author',
            'restore Author',
            'restore-any Author',
            'replicate Author',
            'reorder Author',
            'force-delete Author',
            'force-delete-any Author',

            // Activity Log permissions
            'view-any Activity',
            'view Activity',
            'delete Activity',
            'delete-any Activity',
            'restore Activity',
            'restore-any Activity',
            'force-delete Activity',
            'force-delete-any Activity',

            // Job Queue permissions (individual jobs)
            'view-any Job',
            'view Job',
            'create Job',
            'update Job',
            'delete Job',
            'delete-any Job',
            'restore Job',
            'restore-any Job',
            'replicate Job',
            'reorder Job',
            'force-delete Job',
            'force-delete-any Job',
            'retry Job',
            'retry-any Job',
            'cancel Job',
            'cancel-any Job',

            // Queue Monitor permissions (system-wide queue management)
            'access Queue Monitor',
            'view Queue Statistics',
            'view Queue Performance',
            'view Job Details',
            'view Job Payload',
            'retry Jobs',
            'delete Jobs',
            'clear Queues',
            'manage Queue Workers',
            'control Queues',
            'prune Jobs',
            'export Queue Data',
            'view Queue Configuration',
            'update Queue Configuration',
            'view Queue Logs',
            'view Failed Job Details',
            'batch Process Jobs',
            'force-delete Jobs',
            'cancel Jobs',
            'view Sensitive Job Data',

            // System permissions
            'view Settings',
            'manage Footer',
            'view System',
            'view Health',
            'manage Backup',
            'view Activity Log',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // Create roles
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $editorRole = Role::firstOrCreate([
            'name' => 'Editor',
            'guard_name' => 'web',
        ]);

        $authorRole = Role::firstOrCreate([
            'name' => 'Author',
            'guard_name' => 'web',
        ]);

        // Super Admin gets all permissions
        $superAdminRole->syncPermissions(Permission::all());

        // Admin permissions (everything except sensitive system operations and destructive queue operations)
        $adminPermissions = Permission::whereNotIn('name', [
            'force-delete User',
            'force-delete-any User',
            'delete Role',
            'delete-any Role',
            'force-delete Role',
            'force-delete-any Role',
            'delete Permission',
            'delete-any Permission',
            'force-delete Permission',
            'force-delete-any Permission',
            'clear Queues',
            'force-delete Jobs',
            'update Queue Configuration',
            'view Sensitive Job Data',
        ])->pluck('name')->toArray();
        
        $adminRole->syncPermissions($adminPermissions);

        // Editor permissions (content management focused with basic queue monitoring)
        $editorPermissions = [
            'view-any Post',
            'view Post',
            'create Post',
            'update Post',
            'delete Post',
            'publish Post',
            'unpublish Post',
            'view-any Category',
            'view Category',
            'create Category',
            'update Category',
            'view-any Author',
            'view Author',
            'create Author',
            'update Author',
            'view-any User',
            'view User',
            // Basic queue monitoring for content-related jobs
            'access Queue Monitor',
            'view Queue Statistics',
            'view Job Details',
            'retry Jobs',
            'view-any Job',
            'view Job',
        ];
        
        $editorRole->syncPermissions($editorPermissions);

        // Author permissions (limited content creation with minimal queue monitoring)
        $authorPermissions = [
            'view-any Post',
            'view Post',
            'create Post',
            'update Post', // Limited to own posts via policy
            'view-any Category',
            'view Category',
            'view-any Author',
            'view Author',
            // Minimal queue monitoring for own content jobs
            'view-any Job',
            'view Job',
        ];
        
        $authorRole->syncPermissions($authorPermissions);

        // Create default super admin user if it doesn't exist
        $superAdminUser = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Assign Super Admin role to the default user
        if (!$superAdminUser->hasRole('Super Admin')) {
            $superAdminUser->assignRole('Super Admin');
        }

        $this->command->info('Permissions and roles created successfully!');
        $this->command->info('Default Super Admin user: admin@example.com / password');
    }
}