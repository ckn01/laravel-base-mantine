import { describe, it, expect, vi, beforeEach } from 'vitest';
import { screen, fireEvent } from '@testing-library/react';
import { renderWithMantine, createMockErrorProps } from '../../utils';
import NotFoundPage from '../../../pages/errors/404';

// Mock the useAppearance hook
vi.mock('../../../hooks/use-appearance', () => ({
    useAppearance: vi.fn()
}));

describe('404 Error Page', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        // Mock window.history.back
        window.history.back = vi.fn();
    });

    it('renders 404 page with correct title and content', () => {
        const errorProps = createMockErrorProps({
            status: 404,
            message: 'Page not found'
        });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check page title
        expect(document.title).toBe('404 - Page Not Found');

        // Check status code display
        expect(screen.getByText('404')).toBeInTheDocument();

        // Check title
        expect(screen.getByText('Page Not Found')).toBeInTheDocument();

        // Check description
        expect(screen.getByText(/The page you are looking for might have been removed/)).toBeInTheDocument();
    });

    it('displays custom error message when provided', () => {
        const customMessage = 'This specific page could not be found';
        const errorProps = createMockErrorProps({
            status: 404,
            message: customMessage
        });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        expect(screen.getByText(customMessage)).toBeInTheDocument();
    });

    it('displays default message when no message provided', () => {
        const errorProps = createMockErrorProps({
            status: 404,
            message: ''
        });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        expect(screen.getByText(/The page you are looking for might have been removed/)).toBeInTheDocument();
    });

    it('renders helpful suggestions', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        expect(screen.getByText('Here are some suggestions:')).toBeInTheDocument();
        expect(screen.getByText('• Check if the URL is typed correctly')).toBeInTheDocument();
        expect(screen.getByText('• Try searching for what you\'re looking for')).toBeInTheDocument();
        expect(screen.getByText('• Go back to the previous page')).toBeInTheDocument();
        expect(screen.getByText('• Visit our homepage')).toBeInTheDocument();
    });

    it('renders action buttons with correct functionality', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check Go Back button
        const goBackButton = screen.getByText('Go Back');
        expect(goBackButton).toBeInTheDocument();

        // Check Take me home button  
        const homeButton = screen.getByText('Take me home');
        expect(homeButton).toBeInTheDocument();
        expect(homeButton.closest('a')).toHaveAttribute('href', '/dashboard');
    });

    it('handles go back button click correctly', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        const goBackButton = screen.getByText('Go Back');
        fireEvent.click(goBackButton);

        expect(window.history.back).toHaveBeenCalledOnce();
    });

    it('displays 404 icon correctly', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check that the icon container exists with proper styling
        const iconContainer = screen.getByText('404').parentElement?.previousElementSibling;
        expect(iconContainer).toHaveClass('flex', 'h-24', 'w-24', 'items-center', 'justify-center');
    });

    it('has proper styling classes applied', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        const { container } = renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check main status code styling
        const statusElement = screen.getByText('404');
        expect(statusElement).toHaveClass('text-8xl', 'font-bold', 'text-orange-600', 'dark:text-orange-400');
    });

    it('renders with proper accessibility', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check heading hierarchy
        const mainHeading = screen.getByRole('heading', { level: 2 });
        expect(mainHeading).toHaveTextContent('Page Not Found');

        // Check button accessibility
        const buttons = screen.getAllByRole('button');
        expect(buttons.length).toBeGreaterThan(0);

        // Check links
        const homeLink = screen.getByRole('link', { name: /take me home/i });
        expect(homeLink).toBeInTheDocument();
    });

    it('passes error data to ErrorLayout correctly', () => {
        const errorProps = createMockErrorProps({
            status: 404,
            errorId: 'test-404-error',
            timestamp: '2024-01-01T00:00:00Z',
            supportInfo: {
                email: 'support@test.com'
            }
        });

        renderWithMantine(<NotFoundPage {...errorProps} />);

        // These elements are rendered by ErrorLayout
        expect(screen.getByText('Error ID:')).toBeInTheDocument();
        expect(screen.getByText('test-404-error')).toBeInTheDocument();
        expect(screen.getByText('support@test.com')).toBeInTheDocument();
    });

    it('handles responsive design correctly', () => {
        const errorProps = createMockErrorProps({ status: 404 });

        const { container } = renderWithMantine(<NotFoundPage {...errorProps} />);

        // Check that action buttons have responsive classes
        const buttonContainer = screen.getByText('Go Back').parentElement;
        expect(buttonContainer).toHaveClass('flex', 'flex-col', 'gap-3', 'sm:flex-row');
    });
});
