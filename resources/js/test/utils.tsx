import { render } from '@testing-library/react';
import { MantineProvider } from '@mantine/core';
import { ReactElement } from 'react';
import { ErrorPageProps } from '@/types/errors';

// Custom render function that includes Mantine provider
export function renderWithMantine(ui: ReactElement) {
    return render(ui, {
        wrapper: ({ children }) => (
            <MantineProvider>
                {children}
            </MantineProvider>
        ),
    });
}

// Mock error data factory
export function createMockErrorProps(overrides?: Partial<ErrorPageProps>): ErrorPageProps {
    return {
        status: 404,
        message: 'Test error message',
        errorId: 'test-error-123',
        timestamp: new Date('2024-01-01T00:00:00Z').toISOString(),
        canRetry: false,
        supportInfo: {
            email: 'support@test.com',
            phone: '+1234567890',
            url: 'https://support.test.com'
        },
        context: {
            url: '/test-url',
            userAgent: 'Test User Agent',
            method: 'GET',
            userId: 1
        },
        debug: null,
        ...overrides
    };
}

// Mock error data with debug info
export function createMockErrorPropsWithDebug(overrides?: Partial<ErrorPageProps>): ErrorPageProps {
    return createMockErrorProps({
        debug: {
            exception: 'TestException',
            message: 'Test debug message',
            file: '/path/to/test/file.php',
            line: 42,
            trace: [
                {
                    file: '/path/to/test/file.php',
                    line: 42,
                    function: 'testFunction',
                    class: 'TestClass'
                }
            ]
        },
        ...overrides
    });
}

// Error simulation utilities for tests
export class ErrorSimulator {
    // Simulate network errors
    static simulateNetworkError() {
        return new Error('Network request failed');
    }

    // Simulate validation errors  
    static simulateValidationError(field: string) {
        return {
            message: 'The given data was invalid.',
            errors: {
                [field]: ['The ' + field + ' field is required.']
            }
        };
    }

    // Simulate authentication errors
    static simulateAuthError() {
        return {
            status: 401,
            message: 'Unauthorized access'
        };
    }

    // Simulate server errors
    static simulateServerError() {
        return {
            status: 500,
            message: 'Internal server error'
        };
    }
}

// Test helpers for error scenarios
export const testErrorScenarios = {
    // Common error status codes with expected behavior
    statusCodes: [
        { status: 400, shouldShowRetry: false, expectedMessage: 'Bad Request' },
        { status: 401, shouldShowRetry: false, expectedMessage: 'Unauthorized' },
        { status: 403, shouldShowRetry: false, expectedMessage: 'Forbidden' },
        { status: 404, shouldShowRetry: false, expectedMessage: 'Not Found' },
        { status: 419, shouldShowRetry: true, expectedMessage: 'Page Expired' },
        { status: 429, shouldShowRetry: true, expectedMessage: 'Too Many Requests' },
        { status: 500, shouldShowRetry: false, expectedMessage: 'Server Error' },
        { status: 503, shouldShowRetry: true, expectedMessage: 'Service Unavailable' }
    ],

    // Test data for different error types
    errorTypes: {
        network: {
            status: 0,
            message: 'Network connection failed',
            canRetry: true
        },
        validation: {
            status: 422,
            message: 'Validation failed',
            canRetry: false
        },
        authentication: {
            status: 401,
            message: 'Authentication required',
            canRetry: false
        },
        authorization: {
            status: 403,
            message: 'Access denied',
            canRetry: false
        },
        notFound: {
            status: 404,
            message: 'Resource not found',
            canRetry: false
        },
        server: {
            status: 500,
            message: 'Internal server error',
            canRetry: false
        }
    }
};

// Accessibility test helpers
export const a11yTestHelpers = {
    // Check if element has proper ARIA attributes
    checkAriaAttributes: (element: HTMLElement) => {
        const requiredAttributes = ['role', 'aria-label', 'aria-labelledby'];
        return requiredAttributes.some(attr => element.hasAttribute(attr));
    },

    // Check color contrast (simplified check)
    hasGoodColorContrast: (element: HTMLElement) => {
        const style = getComputedStyle(element);
        const color = style.color;
        const backgroundColor = style.backgroundColor;
        // Simplified contrast check - in real tests you'd use a proper contrast library
        return color \!== backgroundColor;
    },

    // Check keyboard navigation support
    isKeyboardAccessible: (element: HTMLElement) => {
        return element.tabIndex >= 0 || 
               element.tagName === 'BUTTON' || 
               element.tagName === 'A' ||
               element.hasAttribute('role');
    }
};

export { render } from '@testing-library/react';