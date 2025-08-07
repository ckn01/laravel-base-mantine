import { describe, it, expect, vi, beforeEach } from 'vitest';
import { screen } from '@testing-library/react';
import { renderWithMantine, createMockErrorProps, createMockErrorPropsWithDebug } from '../../utils';
import ServerErrorPage from '../../../pages/errors/500';

// Mock the useAppearance hook
vi.mock('../../../hooks/use-appearance', () => ({
    useAppearance: vi.fn()
}));

describe('500 Server Error Page', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders 500 page with correct title and content', () => {
        const errorProps = createMockErrorProps({
            status: 500,
            message: 'Internal server error'
        });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check page title
        expect(document.title).toBe('500 - Internal Server Error');

        // Check status code display
        expect(screen.getByText('500')).toBeInTheDocument();

        // Check title
        expect(screen.getByText('Internal Server Error')).toBeInTheDocument();

        // Check description
        expect(screen.getByText(/something went wrong on our end/i)).toBeInTheDocument();
    });

    it('displays server error specific messaging', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        expect(screen.getByText('Here's what you can do:')).toBeInTheDocument();
        expect(screen.getByText('• Refresh the page to try again')).toBeInTheDocument();
        expect(screen.getByText('• Check back in a few minutes')).toBeInTheDocument();
        expect(screen.getByText('• Contact support if the problem persists')).toBeInTheDocument();
        expect(screen.getByText('• Try accessing a different page')).toBeInTheDocument();
    });

    it('renders action buttons for server errors', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check Refresh Page button
        const refreshButton = screen.getByText('Refresh Page');
        expect(refreshButton).toBeInTheDocument();

        // Check Go Home button  
        const homeButton = screen.getByText('Go Home');
        expect(homeButton).toBeInTheDocument();
        expect(homeButton.closest('a')).toHaveAttribute('href', '/');
    });

    it('has proper error styling with red theme for severity', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check main status code styling uses red theme for server errors
        const statusElement = screen.getByText('500');
        expect(statusElement).toHaveClass('text-8xl', 'font-bold', 'text-red-600', 'dark:text-red-400');
    });

    it('displays server error icon correctly', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check that the icon container has proper error styling
        const iconContainer = screen.getByText('500').parentElement?.previousElementSibling;
        expect(iconContainer).toHaveClass('flex', 'h-24', 'w-24', 'items-center', 'justify-center');
        expect(iconContainer).toHaveClass('bg-red-100', 'dark:bg-red-950/20');
    });

    it('passes error data correctly to ErrorLayout', () => {
        const errorProps = createMockErrorProps({
            status: 500,
            errorId: 'test-500-error',
            timestamp: '2024-01-01T00:00:00Z',
            canRetry: false // Server errors typically shouldn't be retryable
        });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // These elements are rendered by ErrorLayout
        expect(screen.getByText('Error ID:')).toBeInTheDocument();
        expect(screen.getByText('test-500-error')).toBeInTheDocument();
        
        // 500 errors should not be retryable by default
        expect(screen.queryByText('Try Again')).not.toBeInTheDocument();
    });

    it('handles debug mode correctly', () => {
        const errorProps = createMockErrorPropsWithDebug({
            status: 500,
            debug: {
                exception: 'RuntimeException',
                message: 'Division by zero',
                file: '/path/to/controller.php',
                line: 15,
                trace: [
                    {
                        file: '/path/to/controller.php',
                        line: 15,
                        function: 'calculate',
                        class: 'CalculationController'
                    }
                ]
            }
        });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Debug information should be passed to ErrorLayout
        expect(screen.getByText('Error ID:')).toBeInTheDocument();
    });

    it('renders with proper accessibility for critical errors', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check heading hierarchy
        const mainHeading = screen.getByRole('heading', { level: 2 });
        expect(mainHeading).toHaveTextContent('Internal Server Error');

        // Check that refresh and home buttons are accessible
        const refreshButton = screen.getByRole('button', { name: /refresh page/i });
        const homeLink = screen.getByRole('link', { name: /go home/i });
        
        expect(refreshButton).toBeInTheDocument();
        expect(homeLink).toBeInTheDocument();
    });

    it('provides appropriate user guidance for server errors', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Should provide reassuring and helpful guidance
        expect(screen.getByText(/we're working to fix this issue/i)).toBeInTheDocument();
        expect(screen.getByText(/the problem has been reported/i)).toBeInTheDocument();
    });

    it('handles responsive design correctly', () => {
        const errorProps = createMockErrorProps({ status: 500 });

        renderWithMantine(<ServerErrorPage {...errorProps} />);

        // Check that action buttons have responsive classes
        const buttonContainer = screen.getByText('Refresh Page').parentElement;
        expect(buttonContainer).toHaveClass('flex', 'flex-col', 'gap-3', 'sm:flex-row');
    });
});
