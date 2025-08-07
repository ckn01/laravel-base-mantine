import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconLock, IconLogin, IconHome } from '@tabler/icons-react';

interface ForbiddenPageProps extends ErrorPageProps {}

export default function ForbiddenPage(props: ForbiddenPageProps) {
    return (
        <>
            <Head title="403 - Forbidden" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-red-100 dark:bg-red-950/20">
                        <IconLock size={48} className="text-red-600 dark:text-red-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-red-600 dark:text-red-400">
                        403
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Access Forbidden
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'You don\'t have permission to access this resource. This could be due to insufficient privileges or authentication requirements.'}
                    </Text>

                    {/* Reasons */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            This might happen if:
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Your session has expired</li>
                            <li>• You don't have the required permissions</li>
                            <li>• The resource is restricted to certain users</li>
                            <li>• Authentication is required</li>
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