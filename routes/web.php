<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

// Error reporting API routes
Route::prefix('api/errors')->middleware(['throttle:10,1'])->group(function () {
    Route::post('report', [App\Http\Controllers\ErrorReportController::class, 'report'])
        ->name('errors.report');
    Route::get('config', [App\Http\Controllers\ErrorReportController::class, 'config'])
        ->name('errors.config');
});

// Error page routes for testing and fallback
Route::prefix('errors')->name('errors.')->group(function () {
    Route::get('400', function () {
        return Inertia::render('errors/400', [
            'status' => 400,
            'message' => 'Bad Request. The request could not be understood by the server.',
        ]);
    })->name('400');
    
    Route::get('401', function () {
        return Inertia::render('errors/401', [
            'status' => 401,
            'message' => 'Unauthorized. Authentication is required to access this resource.',
        ]);
    })->name('401');
    
    Route::get('403', function () {
        return Inertia::render('errors/403', [
            'status' => 403,
            'message' => 'Forbidden. You don\'t have permission to access this resource.',
        ]);
    })->name('403');
    
    Route::get('404', function () {
        return Inertia::render('errors/404', [
            'status' => 404,
            'message' => 'Page Not Found. The requested page could not be found.',
        ]);
    })->name('404');
    
    Route::get('419', function () {
        return Inertia::render('errors/419', [
            'status' => 419,
            'message' => 'Page Expired. Please refresh the page and try again.',
        ]);
    })->name('419');
    
    Route::get('429', function () {
        return Inertia::render('errors/429', [
            'status' => 429,
            'message' => 'Too Many Requests. Please slow down and try again later.',
        ]);
    })->name('429');
    
    Route::get('500', function () {
        return Inertia::render('errors/500', [
            'status' => 500,
            'message' => 'Server Error. Something went wrong on our end.',
        ]);
    })->name('500');
    
    Route::get('503', function () {
        return Inertia::render('errors/503', [
            'status' => 503,
            'message' => 'Service Unavailable. The server is temporarily unavailable.',
        ]);
    })->name('503');
});

// Test routes for error handling (remove in production)
if (config('app.debug')) {
    Route::prefix('test-errors')->group(function () {
        Route::get('/404', function () {
            abort(404, 'This is a test 404 error');
        });
        
        Route::get('/500', function () {
            throw new Exception('This is a test 500 error');
        });
        
        Route::get('/403', function () {
            abort(403, 'This is a test 403 error');
        });
        
        Route::get('/401', function () {
            abort(401, 'This is a test 401 error');
        });
    });
}

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
