import '../css/app.css';

import theme from '@/theme';
import { createInertiaApp } from '@inertiajs/react';
import { MantineProvider } from '@mantine/core';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: async (name) => {
        try {
            // First try to resolve the requested page
            return await resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx'));
        } catch (error) {
            // If the page is not found and it's an error page, try to find a fallback
            if (name.startsWith('errors/')) {
                const errorCode = name.split('/')[1];
                try {
                    // Try to resolve the specific error page
                    return await resolvePageComponent(`./pages/errors/${errorCode}.tsx`, import.meta.glob('./pages/**/*.tsx'));
                } catch {
                    // Fallback to generic error page if specific one doesn't exist
                    try {
                        return await resolvePageComponent('./pages/errors/default.tsx', import.meta.glob('./pages/**/*.tsx'));
                    } catch {
                        // Final fallback - create a minimal error component
                        return {
                            default: ({ status, message }: { status: number; message: string }) => (
                                <div style={{ padding: '2rem', textAlign: 'center' }}>
                                    <h1>Error {status}</h1>
                                    <p>{message}</p>
                                    <a href="/" style={{ color: '#3b82f6', textDecoration: 'underline' }}>
                                        Return to Home
                                    </a>
                                </div>
                            ),
                        };
                    }
                }
            }
            // Re-throw the error for non-error pages
            throw error;
        }
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(
            <MantineProvider theme={theme}>
                <App {...props} />
            </MantineProvider>,
        );
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on load...
initializeTheme();
