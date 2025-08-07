import type { route as routeFn } from 'ziggy-js';
import type { PageProps } from '@inertiajs/core';
import type { SharedData, ErrorPageProps } from './index.d';

declare global {
    const route: typeof routeFn;
}

declare module '@inertiajs/core' {
    interface PageProps {
        [key: string]: unknown;
    }
}

// Global page props that include shared data and error handling
export interface GlobalPageProps extends PageProps {
    shared?: Partial<SharedData>;
    errors?: Record<string, string>;
}

// Error page specific props
export interface ErrorProps extends ErrorPageProps {
    shared?: Partial<SharedData>;
}
