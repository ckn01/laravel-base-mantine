<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Page Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for custom error pages and error handling behavior.
    |
    */

    /**
     * Error codes that should render custom error pages.
     */
    'renderable_errors' => [
        400, 401, 403, 404, 419, 429, 500, 503
    ],

    /**
     * Default error messages for different status codes.
     */
    'messages' => [
        400 => 'Bad Request. The request could not be understood by the server.',
        401 => 'Unauthorized. Authentication is required to access this resource.',
        403 => 'Forbidden. You don\'t have permission to access this resource.',
        404 => 'Page Not Found. The requested page could not be found.',
        419 => 'Page Expired. Please refresh the page and try again.',
        429 => 'Too Many Requests. Please slow down and try again later.',
        500 => 'Server Error. Something went wrong on our end.',
        503 => 'Service Unavailable. The server is temporarily unavailable.',
    ],

    /**
     * Support contact information.
     */
    'support' => [
        'email' => env('ERROR_SUPPORT_EMAIL', 'support@example.com'),
        'phone' => env('ERROR_SUPPORT_PHONE', null),
        'url' => env('ERROR_SUPPORT_URL', null),
    ],

    /**
     * Error reporting configuration.
     */
    'reporting' => [
        'enabled' => env('ERROR_REPORTING_ENABLED', true),
        'log_client_errors' => env('LOG_CLIENT_ERRORS', false),
        'include_user_agent' => true,
        'include_ip_address' => false, // Privacy consideration
    ],

    /**
     * Retry configuration for different error types.
     */
    'retry' => [
        'allowed_methods' => ['GET', 'HEAD', 'OPTIONS'],
        'delay_seconds' => 3,
        'max_attempts' => 3,
    ],

    /**
     * Feature flags for error handling.
     */
    'features' => [
        'show_debug_info' => env('APP_DEBUG', false),
        'show_error_id' => true,
        'show_retry_button' => true,
        'show_report_button' => env('SHOW_ERROR_REPORT_BUTTON', false),
        'enable_error_boundary' => true,
    ],

];