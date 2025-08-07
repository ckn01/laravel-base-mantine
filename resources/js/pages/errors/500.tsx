import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconAlertTriangle, IconRefresh, IconHome } from '@tabler/icons-react';

interface ServerErrorPageProps extends ErrorPageProps {}

export default function ServerErrorPage(props: ServerErrorPageProps) {
    return (
        <>
            <Head title="500 - Server Error" />
            
            <ErrorLayout error={props} showDebugInfo={!!props.debug}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-red-100 dark:bg-red-950/20">
                        <IconAlertTriangle size={48} className="text-red-600 dark:text-red-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-red-600 dark:text-red-400">
                        500
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Internal Server Error
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'Something went wrong on our servers. We\'re working to fix this issue as quickly as possible.'}
                    </Text>

                    {/* What happened */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            What happened?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• An unexpected error occurred on our server</li>
                            <li>• Our technical team has been automatically notified</li>
                            <li>• We're working to resolve this issue</li>
                            <li>• Your data is safe and secure</li>
                        </ul>
                    </Stack>

                    {/* What can you do */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            What can you do?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Try refreshing the page</li>
                            <li>• Wait a few minutes and try again</li>
                            <li>• Check our status page for updates</li>
                            <li>• Contact support if the problem persists</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Button 
                            variant="filled" 
                            leftSection={<IconRefresh size={16} />}
                            onClick={() => window.location.reload()}
                        >
                            Refresh Page
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