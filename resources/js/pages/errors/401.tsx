import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconUserX, IconLogin, IconHome } from '@tabler/icons-react';

interface UnauthorizedPageProps extends ErrorPageProps {}

export default function UnauthorizedPage(props: UnauthorizedPageProps) {
    return (
        <>
            <Head title="401 - Unauthorized" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-950/20">
                        <IconUserX size={48} className="text-blue-600 dark:text-blue-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-blue-600 dark:text-blue-400">
                        401
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Authentication Required
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'You need to be signed in to access this page. Please log in with your credentials to continue.'}
                    </Text>

                    {/* Reasons */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            This might happen if:
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• You haven't signed in yet</li>
                            <li>• Your session has expired</li>
                            <li>• You were automatically logged out for security</li>
                            <li>• Invalid or expired authentication token</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Button 
                            variant="filled" 
                            leftSection={<IconLogin size={16} />}
                            component="a"
                            href={route('login')}
                        >
                            Sign In
                        </Button>
                        <Button 
                            variant="outline"
                            leftSection={<IconHome size={16} />}
                            component="a"
                            href={"/"}
                        >
                            Go Home
                        </Button>
                    </div>
                </Stack>
            </ErrorLayout>
        </>
    );
}