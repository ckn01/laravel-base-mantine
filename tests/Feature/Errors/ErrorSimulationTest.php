<?php

namespace Tests\Feature\Errors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Test class for error simulation utilities and helpers
 */
class ErrorSimulationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Define test routes for error simulation
        $this->defineTestRoutes();
    }

    /** @test */
    public function it_can_simulate_validation_errors()
    {
        $response = $this->post('/test-validation', [
            'email' => 'invalid-email',
            'password' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function it_can_simulate_authentication_errors()
    {
        $response = $this->get('/test-auth-required');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_simulate_authorization_errors()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/test-admin-only');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_can_simulate_rate_limit_errors()
    {
        // Make multiple requests to trigger rate limiting
        for ($i = 0; $i < 10; $i++) {
            $this->get('/test-rate-limit');
        }

        $response = $this->get('/test-rate-limit');
        $response->assertStatus(429);
    }

    /** @test */
    public function it_can_simulate_server_errors()
    {
        $response = $this->get('/test-server-error');

        $response->assertStatus(500);
    }

    /** @test */
    public function it_can_simulate_maintenance_mode()
    {
        $response = $this->get('/test-maintenance');

        $response->assertStatus(503);
    }

    /** @test */
    public function it_can_simulate_csrf_token_mismatch()
    {
        $response = $this->post('/test-csrf-protected', [
            '_token' => 'invalid-token',
            'data' => 'test'
        ]);

        $response->assertStatus(419);
    }

    /** @test */
    public function it_can_simulate_database_connection_errors()
    {
        // This would require mocking the database connection
        // For demonstration purposes
        $response = $this->get('/test-db-error');

        $response->assertStatus(500);
    }

    /** @test */
    public function it_can_simulate_timeout_errors()
    {
        $response = $this->get('/test-timeout');

        $response->assertStatus(500);
    }

    /** @test */
    public function it_can_simulate_file_not_found_errors()
    {
        $response = $this->get('/test-missing-file');

        $response->assertStatus(500);
    }

    /**
     * Define test routes for error simulation
     */
    protected function defineTestRoutes(): void
    {
        Route::group(['middleware' => 'web'], function () {
            
            Route::post('/test-validation', function () {
                request()->validate([
                    'email' => 'required|email',
                    'password' => 'required|min:6',
                ]);
                return 'success';
            });

            Route::get('/test-auth-required', function () {
                abort(401);
            });

            Route::get('/test-admin-only', function () {
                abort(403);
            })->middleware('auth');

            Route::get('/test-rate-limit', function () {
                abort(429);
            });

            Route::get('/test-server-error', function () {
                throw new \Exception('Simulated server error');
            });

            Route::get('/test-maintenance', function () {
                abort(503);
            });

            Route::post('/test-csrf-protected', function () {
                return 'success';
            });

            Route::get('/test-db-error', function () {
                throw new \Illuminate\Database\QueryException(
                    'SELECT * FROM users',
                    [],
                    new \Exception('Connection refused')
                );
            });

            Route::get('/test-timeout', function () {
                set_time_limit(1);
                sleep(2); // This will timeout
                return 'should not reach here';
            });

            Route::get('/test-missing-file', function () {
                $content = file_get_contents('/nonexistent/file.txt');
                return $content;
            });
        });
    }
}

/**
 * Trait for error simulation in tests
 */
trait SimulatesErrors
{
    /**
     * Simulate a 404 error
     */
    protected function simulate404(): \Illuminate\Testing\TestResponse
    {
        return $this->get('/nonexistent-route-' . uniqid());
    }

    /**
     * Simulate a 401 error
     */
    protected function simulate401(): \Illuminate\Testing\TestResponse
    {
        Route::get('/test-401-' . uniqid(), function () {
            abort(401);
        })->middleware('web');

        return $this->get('/test-401-' . uniqid());
    }

    /**
     * Simulate a 403 error
     */
    protected function simulate403(): \Illuminate\Testing\TestResponse
    {
        $user = User::factory()->create();
        
        Route::get('/test-403-' . uniqid(), function () {
            abort(403);
        })->middleware(['web', 'auth']);

        return $this->actingAs($user)->get('/test-403-' . uniqid());
    }

    /**
     * Simulate a 419 CSRF error
     */
    protected function simulate419(): \Illuminate\Testing\TestResponse
    {
        Route::post('/test-419-' . uniqid(), function () {
            return 'success';
        })->middleware('web');

        return $this->post('/test-419-' . uniqid(), [
            '_token' => 'invalid'
        ]);
    }

    /**
     * Simulate a 429 rate limit error
     */
    protected function simulate429(): \Illuminate\Testing\TestResponse
    {
        Route::get('/test-429-' . uniqid(), function () {
            abort(429);
        })->middleware('web');

        return $this->get('/test-429-' . uniqid());
    }

    /**
     * Simulate a 500 server error
     */
    protected function simulate500(): \Illuminate\Testing\TestResponse
    {
        Route::get('/test-500-' . uniqid(), function () {
            throw new \Exception('Simulated server error');
        })->middleware('web');

        return $this->get('/test-500-' . uniqid());
    }

    /**
     * Simulate a 503 maintenance error
     */
    protected function simulate503(): \Illuminate\Testing\TestResponse
    {
        Route::get('/test-503-' . uniqid(), function () {
            abort(503);
        })->middleware('web');

        return $this->get('/test-503-' . uniqid());
    }

    /**
     * Simulate validation errors
     */
    protected function simulateValidationError(array $data = []): \Illuminate\Testing\TestResponse
    {
        Route::post('/test-validation-' . uniqid(), function () {
            request()->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
            return 'success';
        })->middleware('web');

        return $this->post('/test-validation-' . uniqid(), $data);
    }
}
