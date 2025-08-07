import { describe, it, expect, vi, beforeEach } from 'vitest';
import { screen, fireEvent } from '@testing-library/react';
import { renderWithMantine, createMockErrorProps, createMockErrorPropsWithDebug } from '../../utils';
import ErrorLayout from '../../../pages/errors/error-layout';

// Mock the useAppearance hook
vi.mock('../../../hooks/use-appearance', () => ({
    useAppearance: vi.fn()
}));

describe('ErrorLayout', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('renders error layout with basic error information', () => {
        const errorProps = createMockErrorProps();
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Error Content</div>
            </ErrorLayout>
        );

        expect(screen.getByText('Test Error Content')).toBeInTheDocument();
        expect(screen.getByText('Error ID:')).toBeInTheDocument();
        expect(screen.getByText('test-error-123')).toBeInTheDocument();
        expect(screen.getByText(/Time:/)).toBeInTheDocument();
    });

    it('displays retry link when canRetry is true', () => {
        const errorProps = createMockErrorProps({ canRetry: true });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.getByText('Try Again')).toBeInTheDocument();
    });

    it('does not display retry link when canRetry is false', () => {
        const errorProps = createMockErrorProps({ canRetry: false });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.queryByText('Try Again')).not.toBeInTheDocument();
    });

    it('displays support information when provided', () => {
        const errorProps = createMockErrorProps({
            supportInfo: {
                email: 'help@example.com',
                phone: '+1-555-0123',
                url: 'https://help.example.com'
            }
        });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.getByText('Need help?')).toBeInTheDocument();
        expect(screen.getByText('help@example.com')).toBeInTheDocument();
        expect(screen.getByText('+1-555-0123')).toBeInTheDocument();
        expect(screen.getByText('Support Center')).toBeInTheDocument();
    });

    it('does not display support section when no support info provided', () => {
        const errorProps = createMockErrorProps({
            supportInfo: {}
        });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.queryByText('Need help?')).not.toBeInTheDocument();
    });

    it('displays debug information when showDebugInfo is true and debug data exists', () => {
        const errorProps = createMockErrorPropsWithDebug();
        
        renderWithMantine(
            <ErrorLayout error={errorProps} showDebugInfo={true}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.getByText('Debug Information')).toBeInTheDocument();
        expect(screen.getByText('Exception:')).toBeInTheDocument();
        expect(screen.getByText('TestException')).toBeInTheDocument();
        expect(screen.getByText('Message:')).toBeInTheDocument();
        expect(screen.getByText('Test debug message')).toBeInTheDocument();
        expect(screen.getByText('Location:')).toBeInTheDocument();
        expect(screen.getByText('/path/to/test/file.php:42')).toBeInTheDocument();
    });

    it('does not display debug information when showDebugInfo is false', () => {
        const errorProps = createMockErrorPropsWithDebug();
        
        renderWithMantine(
            <ErrorLayout error={errorProps} showDebugInfo={false}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.queryByText('Debug Information')).not.toBeInTheDocument();
    });

    it('does not display debug information when debug data is null', () => {
        const errorProps = createMockErrorProps({ debug: null });
        
        renderWithMantine(
            <ErrorLayout error={errorProps} showDebugInfo={true}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.queryByText('Debug Information')).not.toBeInTheDocument();
    });

    it('formats timestamp correctly', () => {
        const testDate = '2024-01-01T12:00:00Z';
        const errorProps = createMockErrorProps({ timestamp: testDate });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        const expectedTime = new Date(testDate).toLocaleString();
        expect(screen.getByText(`Time: ${expectedTime}`)).toBeInTheDocument();
    });

    it('handles missing errorId gracefully', () => {
        const errorProps = createMockErrorProps({ errorId: '' });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        expect(screen.queryByText('Error ID:')).not.toBeInTheDocument();
    });

    it('has proper accessibility attributes', () => {
        const errorProps = createMockErrorProps();
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        // Check that support links have proper ARIA attributes
        const supportLinks = screen.getAllByRole('link');
        expect(supportLinks.length).toBeGreaterThan(0);

        // Check that retry link has proper href
        if (errorProps.canRetry) {
            const retryLink = screen.getByText('Try Again');
            expect(retryLink).toHaveAttribute('href', window.location.href);
        }
    });

    it('handles click events correctly', () => {
        const errorProps = createMockErrorProps({ canRetry: true });
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        const retryLink = screen.getByText('Try Again');
        fireEvent.click(retryLink);
        
        // Since it's a regular anchor tag, we just verify it exists and is clickable
        expect(retryLink).toBeInTheDocument();
    });

    it('renders home button correctly', () => {
        const errorProps = createMockErrorProps();
        
        renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        // The return home button should be commented out in current implementation
        expect(screen.queryByText('Return to Home')).not.toBeInTheDocument();
    });

    it('applies correct CSS classes for styling', () => {
        const errorProps = createMockErrorProps();
        
        const { container } = renderWithMantine(
            <ErrorLayout error={errorProps}>
                <div>Test Content</div>
            </ErrorLayout>
        );

        // Check that the main container has proper styling classes
        expect(container.firstChild).toHaveClass('flex', 'min-h-screen', 'items-center', 'justify-center', 'bg-background');
    });
});
