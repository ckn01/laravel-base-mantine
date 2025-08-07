import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconExclamationMark, IconRefresh, IconHome } from '@tabler/icons-react';

interface DefaultErrorPageProps extends ErrorPageProps {}

export default function DefaultErrorPage(props: DefaultErrorPageProps) {
    const getErrorDescription = (status: number): string => {
        switch (status) {
            case 400:
                return 'The request could not be understood due to malformed syntax.';
            case 401:
                return 'Authentication is required to access this resource.';
            case 403:
                return 'You don\'t have permission to access this resource.';
            case 404:
                return 'The requested resource could not be found.';
            case 405:
                return 'The request method is not allowed for this resource.';
            case 419:
                return 'Your session has expired. Please refresh and try again.';
            case 422:
                return 'The request was well-formed but contains semantic errors.';
            case 429:
                return 'Too many requests. Please wait before trying again.';
            case 500:
                return 'An internal server error occurred. Please try again later.';
            case 502:
                return 'Bad gateway. The server received an invalid response.';
            case 503:
                return 'The service is temporarily unavailable. Please try again later.';
            case 504:
                return 'Gateway timeout. The server did not receive a timely response.';
            default:
                return 'An unexpected error occurred. Please try again later.';
        }
    };

    const getErrorColor = (status: number): string => {
        if (status >= 500) return 'red';
        if (status >= 400) return 'orange';
        return 'blue';
    };

    const errorColor = getErrorColor(props.status);
    const colorClasses = {
        red: {
            bg: 'bg-red-100 dark:bg-red-950/20',
            text: 'text-red-600 dark:text-red-400',
        },
        orange: {
            bg: 'bg-orange-100 dark:bg-orange-950/20',
            text: 'text-orange-600 dark:text-orange-400',
        },
        blue: {
            bg: 'bg-blue-100 dark:bg-blue-950/20',
            text: 'text-blue-600 dark:text-blue-400',
        },
    };

    return (
        <>
            <Head title={`${props.status} - Error`} />
            
            <ErrorLayout error={props} showDebugInfo={props.status >= 500 && !!props.debug}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className={`flex h-24 w-24 items-center justify-center rounded-full ${colorClasses[errorColor as keyof typeof colorClasses].bg}`}>
                        <IconExclamationMark size={48} className={colorClasses[errorColor as keyof typeof colorClasses].text} />
                    </div>

                    {/* Status Code */}
                    <div className={`text-8xl font-bold ${colorClasses[errorColor as keyof typeof colorClasses].text}`}>
                        {props.status}
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Oops! Something went wrong
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || getErrorDescription(props.status)}
                    </Text>

                    {/* General guidance */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            What can you do?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Try refreshing the page</li>
                            <li>• Check your internet connection</li>
                            <li>• Go back to the previous page</li>
                            <li>• Contact support if the problem persists</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        {props.canRetry && (
                            <Button 
                                variant="filled" 
                                leftSection={<IconRefresh size={16} />}
                                onClick={() => window.location.reload()}
                            >
                                Refresh Page
                            </Button>
                        )}
                        <Button 
                            variant="outline"
                            leftSection={<IconHome size={16} />}
                            component="a"
                            href={route('dashboard')}
                        >
                            Go Home
                        </Button>
                    </div>

                    {/* Error details for debugging */}
                    {props.status >= 500 && (
                        <Text size="xs" className="max-w-md text-center text-muted-foreground">
                            This error has been automatically logged and our technical team has been notified.
                        </Text>
                    )}
                </Stack>
            </ErrorLayout>
        </>
    );
}