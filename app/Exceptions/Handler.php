<?php

namespace App\Exceptions;

use App\Support\ErrorHelper;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return ErrorHelper::shouldReport($e);
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception): Response
    {
        $response = parent::render($request, $exception);
        
        // Only handle Inertia requests for error pages
        if ($request->inertia() && $this->shouldRenderErrorPage($response->getStatusCode())) {
            return $this->renderInertiaError($request, $response, $exception);
        }

        return $response;
    }

    /**
     * Determine if we should render an error page for this status code.
     */
    protected function shouldRenderErrorPage(int $status): bool
    {
        return in_array($status, config('errors.renderable_errors', [400, 401, 403, 404, 419, 429, 500, 503]));
    }

    /**
     * Render an Inertia error page.
     */
    protected function renderInertiaError(Request $request, Response $response, Throwable $exception): Response
    {
        $status = $response->getStatusCode();
        
        // Log the error for debugging
        if ($status >= 500) {
            Log::error('Server error occurred', [
                'exception' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
                'user_id' => $request->user()?->id,
            ]);
        }

        $errorData = [
            'status' => $status,
            'message' => $this->getErrorMessage($status, $exception),
            'errorId' => ErrorHelper::generateErrorId(),
            'timestamp' => now()->toISOString(),
            'canRetry' => $this->canRetryRequest($request),
            'supportInfo' => config('errors.support', []),
            'context' => ErrorHelper::getErrorContext($request, $exception),
            'debug' => config('errors.features.show_debug_info') ? [
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => collect($exception->getTrace())->take(5)->toArray(),
            ] : null,
        ];

        // Try to render the specific error page, with fallbacks
        try {
            return Inertia::render("errors/{$status}", $errorData)
                ->toResponse($request)
                ->setStatusCode($status);
        } catch (Throwable $e) {
            // If the specific error page doesn't exist, try generic error page
            try {
                return Inertia::render('errors/default', $errorData)
                    ->toResponse($request)
                    ->setStatusCode($status);
            } catch (Throwable $e) {
                // Final fallback - return the original response
                return $response;
            }
        }
    }

    /**
     * Get user-friendly error message for status code.
     */
    protected function getErrorMessage(int $status, Throwable $exception): string
    {
        $messages = config('errors.messages', []);
        
        if (isset($messages[$status])) {
            return $messages[$status];
        }

        return config('errors.features.show_debug_info') 
            ? $exception->getMessage() 
            : 'An unexpected error occurred.';
    }


    /**
     * Determine if the request can be safely retried.
     */
    protected function canRetryRequest(Request $request): bool
    {
        $allowedMethods = config('errors.retry.allowed_methods', ['GET', 'HEAD', 'OPTIONS']);
        return in_array($request->method(), $allowedMethods);
    }
}