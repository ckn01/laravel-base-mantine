import { Head } from '@inertiajs/react';
import { type ErrorPageProps } from '@/types/index.d';
import ErrorLayout from './error-layout';
import { Button, Stack, Text, Title } from '@mantine/core';
import { IconError404, IconSearch } from '@tabler/icons-react';

interface NotFoundPageProps extends ErrorPageProps {}

export default function NotFoundPage(props: NotFoundPageProps) {
    return (
        <>
            <Head title="404 - Page Not Found" />
            
            <ErrorLayout error={props}>
                <Stack gap="lg" align="center">
                    {/* Error Icon */}
                    <div className="flex h-24 w-24 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-950/20">
                        <IconError404 size={48} className="text-orange-600 dark:text-orange-400" />
                    </div>

                    {/* Status Code */}
                    <div className="text-8xl font-bold text-orange-600 dark:text-orange-400">
                        404
                    </div>

                    {/* Title */}
                    <Title order={2} className="text-center text-foreground">
                        Page Not Found
                    </Title>

                    {/* Description */}
                    <Text 
                        size="lg" 
                        className="max-w-md text-center text-muted-foreground"
                    >
                        {props.message || 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'}
                    </Text>

                    {/* Suggestions */}
                    <Stack gap="sm" className="max-w-md text-center">
                        <Text size="sm" fw={500} className="text-foreground">
                            Here are some suggestions:
                        </Text>
                        <ul className="space-y-1 text-left text-sm text-muted-foreground">
                            <li>• Check if the URL is typed correctly</li>
                            <li>• Try searching for what you're looking for</li>
                            <li>• Go back to the previous page</li>
                            <li>• Visit our homepage</li>
                        </ul>
                    </Stack>

                    {/* Actions */}
                    <div className="flex flex-col gap-3 sm:flex-row">
                        <Button 
                            variant="filled" 
                            leftSection={<IconSearch size={16} />}
                            onClick={() => window.history.back()}
                        >
                            Go Back
                        </Button>
                        <Button 
                            variant="outline"
                            component="a"
                            href={route('dashboard')}
                        >
                            Take me home
                        </Button>
                    </div>
                </Stack>
            </ErrorLayout>
        </>
    );
}