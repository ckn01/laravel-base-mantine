<?php

namespace Tests\Feature\Errors;

use App\Support\ErrorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ErrorHelperTest extends TestCase
{
    /** @test */
    public function it_generates_unique_error_ids()
    {
        $id1 = ErrorHelper::generateErrorId();
        $id2 = ErrorHelper::generateErrorId();
        
        $this->assertNotEquals($id1, $id2);
        $this->assertIsString($id1);
        $this->assertIsString($id2);
        $this->assertGreaterThan(8, strlen($id1)); // Should be reasonably long
    }

    /** @test */
    public function it_determines_reportable_exceptions_correctly()
    {
        $exception404 = new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
        $serverException = new \Exception('Server error');
        
        // 404 errors should not be reported by default
        $this->assertFalse(ErrorHelper::shouldReport($exception404));
        
        // Server exceptions should be reported
        $this->assertTrue(ErrorHelper::shouldReport($serverException));
    }

    /** @test */
    public function it_respects_reporting_configuration()
    {
        Config::set('errors.reporting.enabled', false);
        
        $exception = new \Exception('Test exception');
        
        $this->assertFalse(ErrorHelper::shouldReport($exception));
    }

    /** @test */
    public function it_generates_error_context()
    {
        $request = Request::create('/test-url', 'GET', [], [], [], [
            'HTTP_USER_AGENT' => 'Test User Agent',
            'REMOTE_ADDR' => '127.0.0.1'
        ]);
        
        $exception = new \Exception('Test exception');
        
        $context = ErrorHelper::getErrorContext($request, $exception);
        
        $this->assertArrayHasKey('url', $context);
        $this->assertArrayHasKey('method', $context);
        $this->assertArrayHasKey('userAgent', $context);
        $this->assertEquals('/test-url', $context['url']);
        $this->assertEquals('GET', $context['method']);
        $this->assertEquals('Test User Agent', $context['userAgent']);
    }

    /** @test */
    public function it_includes_user_id_when_authenticated()
    {
        $user = \App\Models\User::factory()->create();
        
        $request = Request::create('/test-url');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        
        $exception = new \Exception('Test exception');
        $context = ErrorHelper::getErrorContext($request, $exception);
        
        $this->assertArrayHasKey('userId', $context);
        $this->assertEquals($user->id, $context['userId']);
    }

    /** @test */
    public function it_respects_privacy_settings_for_ip_address()
    {
        Config::set('errors.reporting.include_ip_address', false);
        
        $request = Request::create('/test-url', 'GET', [], [], [], [
            'REMOTE_ADDR' => '127.0.0.1'
        ]);
        
        $exception = new \Exception('Test exception');
        $context = ErrorHelper::getErrorContext($request, $exception);
        
        $this->assertArrayNotHasKey('ip', $context);
        
        // Enable IP logging
        Config::set('errors.reporting.include_ip_address', true);
        
        $context = ErrorHelper::getErrorContext($request, $exception);
        $this->assertArrayHasKey('ip', $context);
        $this->assertEquals('127.0.0.1', $context['ip']);
    }

    /** @test */
    public function it_sanitizes_sensitive_headers()
    {
        $request = Request::create('/test-url', 'GET', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer secret-token',
            'HTTP_X_API_KEY' => 'secret-key',
            'HTTP_ACCEPT' => 'application/json'
        ]);
        
        $exception = new \Exception('Test exception');
        $context = ErrorHelper::getErrorContext($request, $exception);
        
        $this->assertArrayHasKey('headers', $context);
        $this->assertArrayNotHasKey('authorization', $context['headers']);
        $this->assertArrayNotHasKey('x-api-key', $context['headers']);
        $this->assertArrayHasKey('accept', $context['headers']);
    }
}