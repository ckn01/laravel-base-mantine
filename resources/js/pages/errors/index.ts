// Error page components export
export { default as ErrorLayout } from './error-layout';
export { default as DefaultErrorPage } from './default';
export { default as NotFoundPage } from './404';
export { default as UnauthorizedPage } from './401';
export { default as ForbiddenPage } from './403';
export { default as PageExpiredPage } from './419';
export { default as TooManyRequestsPage } from './429';
export { default as ServerErrorPage } from './500';
export { default as ServiceUnavailablePage } from './503';

// Type exports
export type { ErrorPageProps, SupportInfo, ErrorContext, DebugInfo } from '@/types/errors';