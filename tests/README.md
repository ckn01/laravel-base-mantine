# Error Page System Testing

This directory contains comprehensive tests for the error page system including Laravel exception handling and React error components.

## Test Structure

```
tests/
├── Feature/
│   └── Errors/
│       ├── ErrorHandlingTest.php       # Laravel error handling tests
│       ├── ErrorHelperTest.php         # ErrorHelper utility tests  
│       └── ErrorSimulationTest.php     # Error simulation utilities
├── E2E/
│   └── errors/
│       └── error-pages.spec.ts         # End-to-end tests
└── MANUAL_TESTING_GUIDE.md            # Manual testing checklist

resources/js/test/
├── setup.ts                           # Test setup and mocks
├── utils.tsx                          # Testing utilities
└── pages/errors/
    ├── error-layout.test.tsx          # ErrorLayout component tests
    ├── 404.test.tsx                   # 404 page tests
    ├── 401.test.tsx                   # 401 page tests
    └── 500.test.tsx                   # 500 page tests
```

## Running Tests

### Prerequisites

```bash
# Install testing dependencies
npm install

# Ensure Laravel dependencies are installed
composer install
```

### Laravel/PHP Tests

```bash
# Run all Laravel tests
php artisan test

# Run only error-related tests
php artisan test --testsuite=Feature --filter=Error

# Run with coverage
php artisan test --coverage

# Run specific test class
php artisan test tests/Feature/Errors/ErrorHandlingTest.php
```

### React Component Tests

```bash
# Run all React component tests
npm run test

# Run in watch mode
npm run test

# Run with UI
npm run test:ui

# Run with coverage
npm run test:coverage

# Run specific test file
npx vitest run resources/js/test/pages/errors/404.test.tsx
```

### E2E Tests

```bash
# Install Playwright browsers (first time only)
npx playwright install

# Run E2E tests
npm run test:e2e

# Run E2E tests with UI
npm run test:e2e:ui

# Run E2E tests in specific browser
npx playwright test --project=chromium
```

### Run All Tests

```bash
# Run complete test suite
npm run test:all

# Or run individually
npm run test:run
npm run test:e2e
php artisan test --testsuite=Feature --filter=Error
```

## Test Categories

### 1. Laravel Feature Tests

**Purpose**: Test Laravel exception handling, Inertia.js integration, and error configuration.

**Coverage**:
- Exception handler behavior for different error codes
- Inertia response generation for error pages  
- Error context and metadata generation
- Configuration respect and fallback handling
- Error logging and reporting

### 2. React Component Tests

**Purpose**: Test React error page components in isolation.

**Coverage**:
- Component rendering with different props
- User interaction handling (buttons, links)
- Accessibility compliance
- Responsive design behavior
- Error state handling

### 3. E2E Tests

**Purpose**: Test complete error handling flow in real browser environment.

**Coverage**:
- Full error page rendering and navigation
- Cross-browser compatibility
- Performance and loading behavior
- SSR functionality
- User journey completion

### 4. Manual Testing

**Purpose**: Human verification of UX, accessibility, and edge cases.

**Coverage**:
- Visual design and styling
- Accessibility with screen readers
- Real device testing
- Network condition variations
- Business logic validation

## Test Data and Utilities

### Error Simulation

The `SimulatesErrors` trait provides helpers for testing different error scenarios:

```php
use Tests\Feature\Errors\SimulatesErrors;

class MyTest extends TestCase {
    use SimulatesErrors;
    
    public function test_handles_404() {
        $response = $this->simulate404();
        $response->assertStatus(404);
    }
}
```

### React Test Utilities

```typescript
import { renderWithMantine, createMockErrorProps } from '../utils';

// Render component with Mantine provider
const { screen } = renderWithMantine(<ErrorComponent />);

// Create mock error props
const errorProps = createMockErrorProps({
    status: 404,
    message: 'Custom error message'
});
```

### Error Context Creation

```php
// Create error context for testing
$context = ErrorHelper::getErrorContext($request, $exception);

// Mock error props with debug info
$props = createMockErrorPropsWithDebug();
```

## Configuration for Testing

### Test Environment Variables

```env
# .env.testing
APP_ENV=testing
APP_DEBUG=true
ERROR_REPORTING_ENABLED=true
LOG_CLIENT_ERRORS=true
```

### Test Configuration

Tests respect the same configuration as production:
- `config/errors.php` - Error page configuration
- `config/app.php` - Debug and logging settings
- `phpunit.xml` - PHPUnit configuration
- `vitest.config.ts` - Vitest configuration
- `playwright.config.ts` - Playwright configuration

## Continuous Integration

### GitHub Actions Example

```yaml
name: Error Page Tests

on: [push, pull_request]

jobs:
  php-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.2
      - name: Run Laravel Tests
        run: php artisan test --filter=Error

  js-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: 18
      - name: Install dependencies
        run: npm ci
      - name: Run Component Tests
        run: npm run test:run
      - name: Run E2E Tests
        run: npm run test:e2e
```

## Performance Benchmarks

### Expected Performance Metrics

| Test Type | Target Time | Actual |
|-----------|-------------|---------|
| Laravel Feature Tests | < 30s | - |
| React Component Tests | < 10s | - |
| E2E Test Suite | < 2min | - |
| Error Page Load Time | < 2s | - |

### Monitoring

```bash
# Measure test execution time
time php artisan test --filter=Error

# Measure component test time  
time npm run test:run

# Profile E2E performance
npx playwright test --trace=on
```

## Troubleshooting

### Common Issues

1. **Tests failing due to missing dependencies**:
   ```bash
   npm install
   composer install
   ```

2. **Inertia tests failing**:
   - Check Inertia middleware is enabled
   - Verify test routes are set up correctly

3. **React component tests failing**:
   - Check Mantine provider setup
   - Verify mock configurations

4. **E2E tests timing out**:
   - Increase timeout in playwright.config.ts
   - Check if dev server is running

### Debug Commands

```bash
# Debug Laravel tests
php artisan test --filter=Error --stop-on-failure -v

# Debug React tests  
npx vitest run --reporter=verbose

# Debug E2E tests
npx playwright test --debug
```

## Contributing

When adding new error pages or modifying existing ones:

1. Add corresponding feature tests in `tests/Feature/Errors/`
2. Add component tests in `resources/js/test/pages/errors/`
3. Update E2E tests if user flows change
4. Update manual testing guide
5. Ensure all tests pass before submitting PR

## Resources

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Vitest Documentation](https://vitest.dev/)
- [Playwright Documentation](https://playwright.dev/)
- [Testing Library React](https://testing-library.com/docs/react-testing-library/intro/)
- [Mantine Testing Guide](https://mantine.dev/guides/testing/)
