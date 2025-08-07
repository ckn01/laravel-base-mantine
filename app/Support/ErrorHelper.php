<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ErrorHelper
{
    /**
     * Log a client-side error from JavaScript.
     */
    public static function logClientError(array $errorData, Request $request): void
    {
        if (! config('errors.reporting.log_client_errors', false)) {
            return;
        }

        $context = [
            'client_error' => true,
            'error' => $errorData,
            'url' => $request->fullUrl(),
            'user_id' => $request->user()?->id,
        ];

        if (config('errors.reporting.include_user_agent', true)) {
            $context['user_agent'] = $request->userAgent();
        }

        if (config('errors.reporting.include_ip_address', false)) {
            $context['ip_address'] = $request->ip();
        }

        Log::error('Client-side error reported', $context);
    }

    /**
     * Determine if an error should be reported to external services.
     */
    public static function shouldReport(\Throwable $exception): bool
    {
        // Don't report certain exception types
        $ignoreTypes = [
            \Illuminate\Http\Exceptions\HttpResponseException::class,
            \Illuminate\Auth\AuthenticationException::class,
            \Illuminate\Validation\ValidationException::class,
            \Symfony\Component\HttpKernel\Exception\HttpException::class,
        ];

        foreach ($ignoreTypes as $type) {
            if ($exception instanceof $type) {
                return false;
            }
        }

        return config('errors.reporting.enabled', true);
    }

    /**
     * Get error context for debugging.
     */
    public static function getErrorContext(Request $request, \Throwable $exception = null): array
    {
        $context = [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'timestamp' => now()->toISOString(),
            'user_agent' => $request->userAgent(),
        ];

        if ($request->user()) {
            $context['user_id'] = $request->user()->id;
            $context['user_email'] = $request->user()->email;
        }

        if ($exception) {
            $context['exception'] = [
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        return $context;
    }

    /**
     * Generate a unique error report ID.
     */
    public static function generateErrorId(): string
    {
        return 'ERR-' . date('Ymd-His') . '-' . strtoupper(substr(md5(microtime(true) . random_bytes(8)), 0, 6));
    }

    /**
     * Get user-friendly error message based on exception type.
     */
    public static function getUserFriendlyMessage(\Throwable $exception): string
    {
        return match (get_class($exception)) {
            \Illuminate\Database\QueryException::class => 'A database error occurred. Please try again later.',
            \Illuminate\Http\Client\RequestException::class => 'An external service is unavailable. Please try again later.',
            \Illuminate\Filesystem\FilesystemException::class => 'A file operation failed. Please try again.',
            default => 'An unexpected error occurred. Please try again.',
        };
    }
}