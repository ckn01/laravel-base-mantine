# Error Page Components

This directory contains React error page components built with Mantine UI and designed to integrate with Laravel's error handling system.

## Components

### Core Components
- **ErrorLayout** - Base layout component used by all error pages
- **DefaultErrorPage** - Fallback error page for any unhandled status codes

### Specific Error Pages
- **401.tsx** - Unauthorized (authentication required)
- **403.tsx** - Forbidden (access denied)
- **404.tsx** - Not Found (resource not found)
- **419.tsx** - Page Expired (CSRF token expired)
- **429.tsx** - Too Many Requests (rate limiting)
- **500.tsx** - Internal Server Error (server errors)
- **503.tsx** - Service Unavailable (maintenance mode)

## Features

- **Mantine Integration**: Uses Mantine 7.17 components and theme system
- **Dark/Light Mode**: Supports existing theme system with automatic appearance detection
- **Responsive Design**: Mobile-first responsive layout with Tailwind CSS
- **Accessibility**: ARIA labels, keyboard navigation, screen reader friendly
- **TypeScript**: Full type safety with proper interfaces
- **Inertia.js Compatible**: SSR-ready components that work with Laravel's Inertia setup
- **Interactive Elements**: Countdown timers, progress bars, retry mechanisms
- **Debug Information**: Development-friendly error details (when enabled)
- **Support Integration**: Contact information and support links

## Usage

The Laravel exception handler (`app/Exceptions/Handler.php`) automatically renders these components for Inertia requests based on HTTP status codes.

Example Laravel route:
```php
Route::get('/test-error', function () {
    abort(404, 'Custom not found message');
});
```

## Styling

All components use:
- Mantine theme components with consistent sizing (`size="sm"`)
- Tailwind CSS for layout and responsive design
- CSS custom properties for theme colors (`var(--foreground)`, `var(--background)`, etc.)
- Tabler icons for visual elements

## TypeScript Interfaces

Error page props are defined in `/types/errors.ts`:
- `ErrorPageProps` - Main error page data structure
- `SupportInfo` - Contact and support information
- `ErrorContext` - Request context for debugging
- `DebugInfo` - Development debug information