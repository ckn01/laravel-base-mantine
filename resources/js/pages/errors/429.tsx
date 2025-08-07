import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title, Progress } from '@mantine/core';
import { IconClock, IconRefresh, IconHome } from '@tabler/icons-react';
import { useEffect, useState } from 'react';

interface TooManyRequestsPageProps extends ErrorPageProps {}

export default function TooManyRequestsPage(props: TooManyRequestsPageProps) {
    const [countdown, setCountdown] = useState(60);
    const [canRetry, setCanRetry] = useState(false);

    useEffect(() => {
        if (countdown > 0) {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
        } else {
            setCanRetry(true);
        }
    }, [countdown]);

    const progressValue = ((60 - countdown) / 60) * 100;

    return (
        <>
            <Head title="429 - Too Many Requests" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-950/20">
                        <IconClock size={48} className="text-purple-600 dark:text-purple-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-purple-600 dark:text-purple-400">
                        429
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Too Many Requests
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'You\'ve made too many requests in a short period. Please wait a moment before trying again.'}
                    </Text>

                    {/* Countdown Timer */}
                    {!canRetry && (
                        <div className="w-full max-w-md">
                            <Text size="sm" fw={500} className="mb-2 text-center text-foreground">
                                Please wait {countdown} seconds before trying again
                            </Text>
                            <Progress 
                                value={progressValue} 
                                size="lg" 
                                radius="md" 
                                className="mb-2"
                                color="purple"
                                striped
                                animated
                            />
                        </div>
                    )}

                    {canRetry && (
                        <div className="rounded-md bg-green-50 p-4 dark:bg-green-950/20">
                            <Text size="sm" fw={500} className="text-center text-green-700 dark:text-green-300">
                                ✓ You can now try again!
                            </Text>
                        </div>
                    )}

                    {/* Why rate limiting exists */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            Why does this happen?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Rate limiting protects our servers</li>
                            <li>• Ensures fair usage for all users</li>
                            <li>• Prevents abuse and spam</li>
                            <li>• Maintains system stability</li>
                        </ul>
                    </Stack>

                    {/* Tips */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            Tips to avoid this:
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Wait between requests</li>
                            <li>• Don't refresh pages repeatedly</li>
                            <li>• Use the application normally</li>
                            <li>• Contact support for API limits</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Button 
                            variant="filled" 
                            leftSection={<IconRefresh size={16} />}
                            onClick={() => window.location.reload()}
                            disabled={!canRetry}
                        >
                            {canRetry ? 'Try Again' : `Wait ${countdown}s`}
                        </Button>
                        <Button 
                            variant="outline"
                            leftSection={<IconHome size={16} />}
                            component="a"
                            href={route('dashboard')}
                        >
                            Go Home
                        </Button>
                    </div>
                </Stack>
            </ErrorLayout>
        </>
    );
}