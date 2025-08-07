import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/errors';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title, Progress } from '@mantine/core';
import { IconTool, IconRefresh, IconHome } from '@tabler/icons-react';

interface ServiceUnavailablePageProps extends ErrorPageProps {}

export default function ServiceUnavailablePage(props: ServiceUnavailablePageProps) {
    return (
        <>
            <Head title="503 - Service Unavailable" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-yellow-100 dark:bg-yellow-950/20">
                        <IconTool size={48} className="text-yellow-600 dark:text-yellow-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-yellow-600 dark:text-yellow-400">
                        503
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Service Temporarily Unavailable
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'We\'re currently performing scheduled maintenance to improve your experience. We\'ll be back online shortly.'}
                    </Text>

                    {/* Maintenance Progress */}
                    <div className="w-full max-w-md">
                        <Text size="sm" fw={500} className="mb-2 text-center text-foreground">
                            Maintenance in Progress
                        </Text>
                        <Progress 
                            value={75} 
                            size="lg" 
                            radius="md" 
                            className="mb-2"
                            color="yellow"
                            striped
                            animated
                        />
                        <Text size="xs" className="text-center text-muted-foreground">
                            Expected completion time: ~15 minutes
                        </Text>
                    </div>

                    {/* Why is this happening */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            Why is this happening?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Scheduled maintenance and updates</li>
                            <li>• Server upgrades for better performance</li>
                            <li>• Security patches and improvements</li>
                            <li>• Database optimization</li>
                        </ul>
                    </Stack>

                    {/* What you can do */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            What can you do?
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Wait for maintenance to complete</li>
                            <li>• Try again in a few minutes</li>
                            <li>• Follow us on social media for updates</li>
                            <li>• Bookmark this page and return later</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Button 
                            variant="filled" 
                            leftSection={<IconRefresh size={16} />}
                            onClick={() => window.location.reload()}
                        >
                            Check Again
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

                    {/* Status Updates */}
                    <Text size="xs" className="text-center text-muted-foreground">
                        For real-time updates, check our{' '}
                        <Text component="a" href="#" className="text-accent hover:text-accent/80">
                            status page
                        </Text>
                        {' '}or follow us on social media.
                    </Text>
                </Stack>
            </ErrorLayout>
        </>
    );
}