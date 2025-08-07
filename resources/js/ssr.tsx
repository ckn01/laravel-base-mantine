import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import ReactDOMServer from 'react-dom/server';
import { type RouteName, route } from 'ziggy-js';
import { MantineProvider } from '@mantine/core';
import theme from '@/theme';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
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
        setup: ({ App, props }) => {
            /* eslint-disable */
            // @ts-expect-error
            global.route<RouteName> = (name, params, absolute) =>
                route(name, params as any, absolute, {
                    // @ts-expect-error
                    ...page.props.ziggy,
                    // @ts-expect-error
                    location: new URL(page.props.ziggy.location),
                });
            /* eslint-enable */

            return (
                <MantineProvider theme={theme}>
                    <App {...props} />
                </MantineProvider>
            );
        },
    }),
);
