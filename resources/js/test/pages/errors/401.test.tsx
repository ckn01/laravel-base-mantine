import { describe, it, expect, vi, beforeEach } from 'vitest';
import { screen } from '@testing-library/react';
import { renderWithMantine, createMockErrorProps } from '../../utils';
import UnauthorizedPage from '../../../pages/errors/401';

// Mock the useAppearance hook
vi.mock('../../../hooks/use-appearance', () => ({
    useAppearance: vi.fn()
}));

describe('401 Unauthorized Error Page', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders 401 page with correct title and content', () => {
        const errorProps = createMockErrorProps({
            status: 401,
            message: 'Authentication required'
        });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check page title
        expect(document.title).toBe('401 - Unauthorized');

        // Check status code display
        expect(screen.getByText('401')).toBeInTheDocument();

        // Check title
        expect(screen.getByText('Unauthorized Access')).toBeInTheDocument();

        // Check description contains authentication message
        expect(screen.getByText(/you need to be signed in to access this page/i)).toBeInTheDocument();
    });

    it('displays custom error message when provided', () => {
        const customMessage = 'Please log in to continue';
        const errorProps = createMockErrorProps({
            status: 401,
            message: customMessage
        });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        expect(screen.getByText(customMessage)).toBeInTheDocument();
    });

    it('displays default message when no message provided', () => {
        const errorProps = createMockErrorProps({
            status: 401,
            message: ''
        });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        expect(screen.getByText(/you need to be signed in to access this page/i)).toBeInTheDocument();
    });

    it('renders authentication-specific suggestions', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        expect(screen.getByText('Here are some suggestions:')).toBeInTheDocument();
        expect(screen.getByText('• Sign in to your account')).toBeInTheDocument();
        expect(screen.getByText('• Check your login credentials')).toBeInTheDocument();
        expect(screen.getByText('• Contact support if you believe this is an error')).toBeInTheDocument();
        expect(screen.getByText('• Return to the homepage')).toBeInTheDocument();
    });

    it('renders sign in and home buttons', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check Sign In button
        const signInButton = screen.getByText('Sign In');
        expect(signInButton).toBeInTheDocument();
        expect(signInButton.closest('a')).toHaveAttribute('href', '/login');

        // Check Go Home button  
        const homeButton = screen.getByText('Go Home');
        expect(homeButton).toBeInTheDocument();
        expect(homeButton.closest('a')).toHaveAttribute('href', '/');
    });

    it('displays security-related icon correctly', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check that the icon container exists with proper styling for unauthorized access
        const iconContainer = screen.getByText('401').parentElement?.previousElementSibling;
        expect(iconContainer).toHaveClass('flex', 'h-24', 'w-24', 'items-center', 'justify-center');
        expect(iconContainer).toHaveClass('bg-red-100', 'dark:bg-red-950/20');
    });

    it('has proper security-focused styling', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check main status code styling uses red theme for security
        const statusElement = screen.getByText('401');
        expect(statusElement).toHaveClass('text-8xl', 'font-bold', 'text-red-600', 'dark:text-red-400');
    });

    it('renders with proper accessibility', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check heading hierarchy
        const mainHeading = screen.getByRole('heading', { level: 2 });
        expect(mainHeading).toHaveTextContent('Unauthorized Access');

        // Check links have proper accessibility
        const signInLink = screen.getByRole('link', { name: /sign in/i });
        expect(signInLink).toBeInTheDocument();
        
        const homeLink = screen.getByRole('link', { name: /go home/i });
        expect(homeLink).toBeInTheDocument();
    });

    it('passes error data to ErrorLayout correctly', () => {
        const errorProps = createMockErrorProps({
            status: 401,
            errorId: 'test-401-error',
            timestamp: '2024-01-01T00:00:00Z',
            canRetry: false
        });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // These elements are rendered by ErrorLayout
        expect(screen.getByText('Error ID:')).toBeInTheDocument();
        expect(screen.getByText('test-401-error')).toBeInTheDocument();
        
        // 401 errors should not be retryable without proper authentication
        expect(screen.queryByText('Try Again')).not.toBeInTheDocument();
    });

    it('handles responsive design correctly', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Check that action buttons have responsive classes
        const buttonContainer = screen.getByText('Sign In').parentElement;
        expect(buttonContainer).toHaveClass('flex', 'flex-col', 'gap-3', 'sm:flex-row');
    });

    it('uses appropriate security-focused messaging', () => {
        const errorProps = createMockErrorProps({ status: 401 });

        renderWithMantine(<UnauthorizedPage {...errorProps} />);

        // Should focus on authentication rather than general access issues
        expect(screen.getByText(/you need to be signed in/i)).toBeInTheDocument();
        expect(screen.getByText(/sign in to your account/i)).toBeInTheDocument();
    });
});
