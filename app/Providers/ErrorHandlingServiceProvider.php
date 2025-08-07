<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class ErrorHandlingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share error handling helpers with Inertia
        Inertia::share([
            'errorHelpers' => fn (Request $request) => [
                'canRetry' => $this->canRetryRequest($request),
                'supportInfo' => config('errors.support', []),
                'features' => config('errors.features', []),
                'retryConfig' => config('errors.retry', []),
                'reportId' => $this->generateErrorReportId(),
            ],
        ]);
    }

    /**
     * Determine if the current request can be retried safely.
     */
    protected function canRetryRequest(Request $request): bool
    {
        $allowedMethods = config('errors.retry.allowed_methods', ['GET', 'HEAD', 'OPTIONS']);
        return in_array($request->method(), $allowedMethods);
    }

    /**
     * Generate a unique error report ID for tracking purposes.
     */
    protected function generateErrorReportId(): string
    {
        return 'ERR-' . date('Ymd') . '-' . substr(md5(microtime(true) . random_bytes(8)), 0, 8);
    }
}