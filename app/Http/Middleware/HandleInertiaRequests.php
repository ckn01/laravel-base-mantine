<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
            'errors' => fn () => $request->session()->get('errors') 
                ? $request->session()->get('errors')->getBag('default')->getMessages() 
                : (object) [],
            // Error context for error pages
            'errorContext' => fn () => $this->getErrorContext($request),
        ];
    }

    /**
     * Get error context for error pages
     */
    protected function getErrorContext(Request $request): array
    {
        $context = [
            'canGoBack' => $request->header('referer') && 
                          $request->header('referer') !== $request->url(),
            'previousUrl' => $request->header('referer'),
            'currentUrl' => $request->fullUrl(),
            'userAgent' => $request->userAgent(),
            'timestamp' => now()->toISOString(),
        ];

        // Add authentication context for permission errors
        if ($request->user()) {
            $context['isAuthenticated'] = true;
            $context['userRole'] = $request->user()->role ?? 'user';
        } else {
            $context['isAuthenticated'] = false;
            $context['loginUrl'] = route('login');
        }

        // Add CSRF context for token mismatch errors
        if ($request->session()->token()) {
            $context['csrfToken'] = $request->session()->token();
        }

        return $context;
    }
}
