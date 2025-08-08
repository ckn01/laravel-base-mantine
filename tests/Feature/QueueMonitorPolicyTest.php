<?php

namespace Tests\Feature;

use App\Models\User;
use App\Policies\QueueMonitorPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class QueueMonitorPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create permissions
        $this->createQueuePermissions();
    }

    protected function createQueuePermissions(): void
    {
        $permissions = [
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
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }
    }

    /** @test */
    public function super_admin_can_access_all_queue_monitor_features()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());
        
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $policy = new QueueMonitorPolicy();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->viewStatistics($user));
        $this->assertTrue($policy->retryJobs($user));
        $this->assertTrue($policy->deleteJobs($user));
        $this->assertTrue($policy->clearQueues($user));
        $this->assertTrue($policy->manageWorkers($user));
        $this->assertTrue($policy->viewSensitiveData($user));
    }

    /** @test */
    public function admin_has_limited_queue_monitor_access()
    {
        $role = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $permissions = Permission::whereNotIn('name', [
            'clear Queues',
            'force-delete Jobs',
            'update Queue Configuration',
            'view Sensitive Job Data',
        ])->get();
        $role->syncPermissions($permissions);

        $user = User::factory()->create();
        $user->assignRole('Admin');

        $policy = new QueueMonitorPolicy();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->viewStatistics($user));
        $this->assertTrue($policy->retryJobs($user));
        $this->assertTrue($policy->deleteJobs($user));
        $this->assertFalse($policy->clearQueues($user));
        $this->assertTrue($policy->manageWorkers($user));
        $this->assertFalse($policy->viewSensitiveData($user));
    }

    /** @test */
    public function editor_has_basic_queue_monitor_access()
    {
        $role = Role::create(['name' => 'Editor', 'guard_name' => 'web']);
        $permissions = Permission::whereIn('name', [
            'access Queue Monitor',
            'view Queue Statistics',
            'view Job Details',
            'retry Jobs',
        ])->get();
        $role->syncPermissions($permissions);

        $user = User::factory()->create();
        $user->assignRole('Editor');

        $policy = new QueueMonitorPolicy();

        $this->assertTrue($policy->viewAny($user));
        $this->assertTrue($policy->viewStatistics($user));
        $this->assertTrue($policy->viewJobDetails($user));
        $this->assertTrue($policy->retryJobs($user));
        $this->assertFalse($policy->deleteJobs($user));
        $this->assertFalse($policy->clearQueues($user));
        $this->assertFalse($policy->manageWorkers($user));
        $this->assertFalse($policy->viewSensitiveData($user));
    }

    /** @test */
    public function author_has_minimal_queue_monitor_access()
    {
        $role = Role::create(['name' => 'Author', 'guard_name' => 'web']);
        // Author has no queue monitor permissions

        $user = User::factory()->create();
        $user->assignRole('Author');

        $policy = new QueueMonitorPolicy();

        $this->assertFalse($policy->viewAny($user));
        $this->assertFalse($policy->viewStatistics($user));
        $this->assertFalse($policy->retryJobs($user));
        $this->assertFalse($policy->deleteJobs($user));
        $this->assertFalse($policy->manageWorkers($user));
    }

    /** @test */
    public function gate_definitions_work_correctly()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('access Queue Monitor');
        $user->givePermissionTo('view Queue Statistics');
        $user->givePermissionTo('retry Jobs');

        $this->assertTrue(Gate::forUser($user)->allows('access Queue Monitor'));
        $this->assertTrue(Gate::forUser($user)->allows('view Queue Statistics'));
        $this->assertTrue(Gate::forUser($user)->allows('retry Jobs'));
        $this->assertFalse(Gate::forUser($user)->allows('clear Queues'));
        $this->assertFalse(Gate::forUser($user)->allows('view Sensitive Job Data'));
    }

    /** @test */
    public function user_without_permissions_cannot_access_queue_monitor()
    {
        $user = User::factory()->create();

        $policy = new QueueMonitorPolicy();

        $this->assertFalse($policy->viewAny($user));
        $this->assertFalse($policy->viewStatistics($user));
        $this->assertFalse($policy->retryJobs($user));
        $this->assertFalse($policy->deleteJobs($user));
        $this->assertFalse($policy->manageWorkers($user));

        $this->assertFalse(Gate::forUser($user)->allows('access Queue Monitor'));
        $this->assertFalse(Gate::forUser($user)->allows('retry Jobs'));
    }
}