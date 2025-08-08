<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Introduction -->
        <x-filament::card>
            <x-slot name="heading">
                Queue Monitor Permissions System
            </x-slot>
            
            <div class="prose dark:prose-invert max-w-none">
                <p class="lead">
                    This Laravel application implements a comprehensive permission system for queue monitoring and management. 
                    The system uses <strong>Spatie Laravel Permission</strong> package with role-based access control 
                    integrated with <strong>Filament Jobs Monitor</strong> plugin.
                </p>
                
                <p>
                    The permission system provides granular control over queue operations, from basic monitoring 
                    to advanced management tasks, ensuring secure and controlled access to your application's queue system.
                </p>
            </div>
        </x-filament::card>

        <!-- Permissions Structure -->
        <x-filament::card>
            <x-slot name="heading">
                Permission Categories
            </x-slot>

            <div class="space-y-6">
                @foreach($permissionsStructure as $category => $permissions)
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $category }}</h3>
                    <div class="space-y-2">
                        @foreach($permissions as $permission => $description)
                        <div class="flex items-start space-x-3">
                            <code class="flex-shrink-0 px-2 py-1 text-xs bg-gray-100 dark:bg-gray-800 rounded font-mono">
                                {{ $permission }}
                            </code>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $description }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::card>

        <!-- Role-Based Permissions -->
        <x-filament::card>
            <x-slot name="heading">
                Role-Based Permission Assignment
            </x-slot>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($rolePermissions as $role => $details)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center space-x-2 mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $role }}</h3>
                        @if($role === 'Super Admin')
                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full">
                                Full Access
                            </span>
                        @elseif($role === 'Admin')
                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 rounded-full">
                                High Access
                            </span>
                        @elseif($role === 'Editor')
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                Moderate Access
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200 rounded-full">
                                Limited Access
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ $details['description'] }}</p>

                    <div class="space-y-3">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Permissions:</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                {{ $details['permissions'] }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Restrictions:</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-800 p-2 rounded">
                                {{ $details['restrictions'] }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::card>

        <!-- Security Considerations -->
        <x-filament::card>
            <x-slot name="heading">
                Security Considerations
            </x-slot>

            <div class="space-y-4">
                @foreach($securityConsiderations as $area => $details)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">{{ $area }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($details['risk'] === 'High') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @elseif($details['risk'] === 'Medium') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @endif">
                            {{ $details['risk'] }} Risk
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $details['description'] }}</p>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded p-3">
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-1">Mitigation:</h4>
                        <p class="text-sm text-blue-800 dark:text-blue-200">{{ $details['mitigation'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::card>

        <!-- Usage Guidelines -->
        <x-filament::card>
            <x-slot name="heading">
                Usage Guidelines
            </x-slot>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($usageGuidelines as $category => $guidelines)
                <div class="space-y-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $category }}</h3>
                    <ul class="space-y-2">
                        @foreach($guidelines as $guideline)
                        <li class="flex items-start space-x-2">
                            <svg class="flex-shrink-0 w-4 h-4 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $guideline }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </x-filament::card>

        <!-- Implementation Details -->
        <x-filament::card>
            <x-slot name="heading">
                Implementation Details
            </x-slot>

            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">Key Components</h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">JobPolicy.php</code> - Individual job authorization</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">QueueMonitorPolicy.php</code> - System-wide queue operations</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">QueueMonitorResource.php</code> - Custom Filament resource</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">FilamentAuthorizationMiddleware.php</code> - Route-level authorization</li>
                        </ul>
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100">Configuration Files</h3>
                        <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">filament-jobs-monitor.php</code> - Plugin configuration</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">PermissionSeeder.php</code> - Permission definitions</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">AuthServiceProvider.php</code> - Gate definitions</li>
                            <li><code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-800 rounded text-xs">AdminPanelProvider.php</code> - Filament configuration</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-2">Custom Features</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>• Role-based navigation badge showing failed job count</li>
                        <li>• Granular action-level authorization for queue operations</li>
                        <li>• Middleware-based route protection for queue monitor endpoints</li>
                        <li>• Bulk operation permissions with safety confirmations</li>
                        <li>• Sensitive data access controls for job payloads</li>
                    </ul>
                </div>
            </div>
        </x-filament::card>
    </div>
</x-filament-panels::page>