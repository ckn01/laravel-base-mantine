<?php

namespace Tests\Feature\Errors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure error pages are enabled
        Config::set('errors.renderable_errors', [400, 401, 403, 404, 419, 429, 500, 503]);
        Config::set('errors.features.show_debug_info', false);
    }

    /** @test */
    public function it_renders_404_error_page_for_missing_routes()
    {
        $response = $this->get('/nonexistent-route');

        $response->assertStatus(404);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/404')
                 ->has('status')
                 ->has('message')
                 ->has('errorId')
                 ->has('timestamp')
                 ->has('canRetry')
                 ->has('supportInfo')
        );
    }

    /** @test */
    public function it_renders_401_error_page_for_unauthenticated_requests()
    {
        Route::get('/test-auth', function () {
            abort(401);
        })->middleware('web');

        $response = $this->get('/test-auth');

        $response->assertStatus(401);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/401')
                 ->where('status', 401)
                 ->has('message')
                 ->has('errorId')
        );
    }

    /** @test */
    public function it_renders_403_error_page_for_forbidden_requests()
    {
        Route::get('/test-forbidden', function () {
            abort(403);
        })->middleware('web');

        $response = $this->get('/test-forbidden');

        $response->assertStatus(403);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/403')
                 ->where('status', 403)
        );
    }

    /** @test */
    public function it_renders_419_csrf_error_page()
    {
        Route::post('/test-csrf', function () {
            return 'success';
        })->middleware('web');

        $response = $this->post('/test-csrf');

        $response->assertStatus(419);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/419')
                 ->where('status', 419)
                 ->where('canRetry', true)
        );
    }

    /** @test */
    public function it_renders_429_rate_limit_error_page()
    {
        Route::get('/test-throttle', function () {
            abort(429);
        })->middleware('web');

        $response = $this->get('/test-throttle');

        $response->assertStatus(429);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/429')
                 ->where('status', 429)
                 ->where('canRetry', true)
        );
    }

    /** @test */
    public function it_renders_500_error_page_for_server_errors()
    {
        Route::get('/test-server-error', function () {
            throw new \Exception('Test server error');
        })->middleware('web');

        $response = $this->get('/test-server-error');

        $response->assertStatus(500);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/500')
                 ->where('status', 500)
                 ->where('canRetry', false)
        );
    }

    /** @test */
    public function it_renders_503_maintenance_error_page()
    {
        Route::get('/test-maintenance', function () {
            abort(503);
        })->middleware('web');

        $response = $this->get('/test-maintenance');

        $response->assertStatus(503);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/503')
                 ->where('status', 503)
        );
    }

    /** @test */
    public function it_includes_error_context_information()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/nonexistent-route');

        $response->assertStatus(404);
        $response->assertInertia(fn (Assert $page) => 
            $page->component('errors/404')
                 ->has('context.url')
                 ->has('context.userAgent')
                 ->has('context.method')
                 ->has('context.userId')
        );
    }

    /** @test */
    public function it_includes_support_information()
    {
        Config::set('errors.support', [
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'url' => 'https://support.example.com'
        ]);

        $response = $this->get('/nonexistent-route');

        $response->assertInertia(fn (Assert $page) => 
            $page->has('supportInfo.email')
                 ->has('supportInfo.phone')
                 ->has('supportInfo.url')
        );
    }

    /** @test */
    public function it_shows_debug_info_when_enabled_in_debug_mode()
    {
        Config::set('errors.features.show_debug_info', true);

        Route::get('/test-debug', function () {
            throw new \Exception('Test debug exception');
        })->middleware('web');

        $response = $this->get('/test-debug');

        $response->assertInertia(fn (Assert $page) => 
            $page->has('debug.exception')
                 ->has('debug.message')
                 ->has('debug.file')
                 ->has('debug.line')
                 ->has('debug.trace')
        );
    }

    /** @test */
    public function it_hides_debug_info_in_production_mode()
    {
        Config::set('errors.features.show_debug_info', false);

        Route::get('/test-no-debug', function () {
            throw new \Exception('Test exception');
        })->middleware('web');

        $response = $this->get('/test-no-debug');

        $response->assertInertia(fn (Assert $page) => 
            $page->where('debug', null)
        );
    }

    /** @test */
    public function it_determines_retry_capability_correctly()
    {
        // GET requests should be retryable
        $response = $this->get('/nonexistent-route');
        $response->assertInertia(fn (Assert $page) => 
            $page->where('canRetry', false) // 404 should not be retryable
        );

        // Test retryable error
        Route::get('/test-retryable', function () {
            abort(503); // Service unavailable should be retryable
        })->middleware('web');

        $response = $this->get('/test-retryable');
        $response->assertInertia(fn (Assert $page) => 
            $page->where('canRetry', true)
        );
    }

    /** @test */
    public function it_falls_back_to_default_error_page_when_specific_page_missing()
    {
        // Temporarily rename error page to simulate missing file
        Config::set('errors.renderable_errors', [999]); // Non-existent error page

        Route::get('/test-fallback', function () {
            abort(999);
        })->middleware('web');

        $response = $this->get('/test-fallback');
        
        // Should fall back to default error handling
        $response->assertStatus(999);
    }

    /** @test */
    public function it_logs_server_errors_with_context()
    {
        Log::spy();

        Route::get('/test-logging', function () {
            throw new \Exception('Test logging exception');
        })->middleware('web');

        $this->get('/test-logging');

        Log::shouldHaveReceived('error')
           ->once()
           ->with('Server error occurred', \Mockery::type('array'));
    }

    /** @test */
    public function it_generates_unique_error_ids()
    {
        $response1 = $this->get('/nonexistent-route-1');
        $response2 = $this->get('/nonexistent-route-2');

        $errorId1 = $response1->getOriginalContent()->getData()['page']['props']['errorId'];
        $errorId2 = $response2->getOriginalContent()->getData()['page']['props']['errorId'];

        $this->assertNotEquals($errorId1, $errorId2);
        $this->assertIsString($errorId1);
        $this->assertIsString($errorId2);
    }

    /** @test */
    public function it_respects_error_configuration()
    {
        // Disable error page rendering for specific status
        Config::set('errors.renderable_errors', [500]); // Only 500 errors

        $response = $this->get('/nonexistent-route');

        // Should not render custom error page, fall back to Laravel default
        $response->assertStatus(404);
        // This would not be an Inertia response in this case
    }

    /** @test */
    public function it_handles_non_inertia_requests_correctly()
    {
        $response = $this->get('/nonexistent-route', [
            'Accept' => 'application/json'
        ]);

        $response->assertStatus(404);
        $response->assertJson([
            'message' => 'The route nonexistent-route could not be found.'
        ]);
    }
}