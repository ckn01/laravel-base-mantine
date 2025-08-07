import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconShieldX, IconRefresh, IconHome } from '@tabler/icons-react';

interface PageExpiredPageProps extends ErrorPageProps {}

export default function PageExpiredPage(props: PageExpiredPageProps) {
    return (
        <>
            <Head title="419 - Page Expired" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-950/20">
                        <IconShieldX size={48} className="text-orange-600 dark:text-orange-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-orange-600 dark:text-orange-400">
                        419
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Page Expired
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'Your session has expired for security reasons. This usually happens when a page has been left open for too long.'}
                    </Text>

                    {/* Why this happens */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            Why did this happen?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• CSRF token has expired for security</li>
                            <li>• Page was open for an extended period</li>
                            <li>• Session timeout for your protection</li>
                            <li>• Form submission took too long</li>
                        </ul>
                    </Stack>

                    {/* What to do */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            What can you do?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Refresh the page to get a new session</li>
                            <li>• Try submitting your form again</li>
                            <li>• Make sure to save your work regularly</li>
                            <li>• Contact support if this keeps happening</li>
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

                    {/* Security Note */}
                    <Text size="xs" className="max-w-md text-center text-muted-foreground">
                        <strong>Security Note:</strong> This protection prevents unauthorized actions on your account. 
                        Your data is safe and secure.
                    </Text>
                </Stack>
            </ErrorLayout>
        </>
    );
}