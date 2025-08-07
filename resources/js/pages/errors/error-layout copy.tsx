import { useAppearance } from '@/hooks/use-appearance';
import { type ErrorPageProps } from '@/types/errors';
import { Anchor, Box, Container, Group, Paper, Stack, Text, Title, Button } from '@mantine/core';
import { IconHome } from '@tabler/icons-react';
import { type ReactNode } from 'react';

interface ErrorLayoutProps {
    children: ReactNode;
    error: ErrorPageProps;
    showDebugInfo?: boolean;
}

export default function ErrorLayout({ children, error, showDebugInfo = false }: ErrorLayoutProps) {
    useAppearance();

    // <div className="flex min-h-screen items-center justify-center bg-background p-2 sm:p-4 md:p-6 lg:p-8">
    // <div className="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
    //     <Paper 
    //         p={{ base: 'md', sm: 'lg', md: 'xl' }} 
    //         radius="md" 
    //         className="border border-border bg-background/50 backdrop-blur-sm mx-2 sm:mx-4"
    //     ></Paper>

    return (
        // <div className="flex min-h-screen items-center justify-center bg-background p-4">
        <div className="flex min-h-screen items-center justify-center bg-background p-2 sm:p-4 md:p-6 lg:p-8">
            {/* <Container size="sm"> */}
            <Container className='w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl'>
                <Paper p="xl" radius="md" className="border border-border bg-background/50 backdrop-blur-sm">
                    <Stack gap="xl">
                        {/* Main Error Content */}
                        <div className="text-center">
                            {children}
                        </div>

                        {/* Error Information */}
                        <Stack gap="sm" className="text-center">
                            {error.errorId && (
                                <Text size="sm" className="text-muted-foreground">
                                    Error ID: <code className="rounded bg-muted px-2 py-1 text-xs">{error.errorId}</code>
                                </Text>
                            )}

                            <Group justify="center" gap="lg" className="text-sm text-muted-foreground">
                                <Text>Time: {new Date(error.timestamp || Date.now()).toLocaleString()}</Text>
                                {error.canRetry && (
                                    <Anchor 
                                        href={window.location.href} 
                                        className="text-accent hover:text-accent/80"
                                    >
                                        Try Again
                                    </Anchor>
                                )}
                            </Group>
                        </Stack>

                        {/* Support Information */}
                        {(error.supportInfo?.email || error.supportInfo?.phone || error.supportInfo?.url) && (
                            <Box className="rounded-md bg-muted/50 p-4 text-center">
                                <Text size="sm" fw={500} className="mb-2 text-foreground">
                                    Need help?
                                </Text>
                                <Group justify="center" gap="md" className="text-sm">
                                    {error.supportInfo.email && (
                                        <Anchor 
                                            href={`mailto:${error.supportInfo.email}`}
                                            className="text-accent hover:text-accent/80"
                                        >
                                            {error.supportInfo.email}
                                        </Anchor>
                                    )}
                                    {error.supportInfo.phone && (
                                        <Anchor 
                                            href={`tel:${error.supportInfo.phone}`}
                                            className="text-accent hover:text-accent/80"
                                        >
                                            {error.supportInfo.phone}
                                        </Anchor>
                                    )}
                                    {error.supportInfo.url && (
                                        <Anchor 
                                            href={error.supportInfo.url}
                                            target="_blank"
                                            className="text-accent hover:text-accent/80"
                                        >
                                            Support Center
                                        </Anchor>
                                    )}
                                </Group>
                            </Box>
                        )}

                        {/* Debug Information */}
                        {showDebugInfo && error.debug && (
                            <Box className="rounded-md bg-red-50 p-4 text-left dark:bg-red-950/20">
                                <Text size="sm" fw={500} className="mb-2 text-red-700 dark:text-red-300">
                                    Debug Information
                                </Text>
                                <Stack gap="xs" className="text-xs font-mono">
                                    <Text className="text-red-600 dark:text-red-400">
                                        <strong>Exception:</strong> {error.debug.exception}
                                    </Text>
                                    <Text className="text-red-600 dark:text-red-400">
                                        <strong>Message:</strong> {error.debug.message}
                                    </Text>
                                    {error.debug.file && error.debug.line && (
                                        <Text className="text-red-600 dark:text-red-400">
                                            <strong>Location:</strong> {error.debug.file}:{error.debug.line}
                                        </Text>
                                    )}
                                </Stack>
                            </Box>
                        )}

                        {/* Home Link */}

                        <Group justify="center">
                            <Button component="a" leftSection={<IconHome />} variant="default" href="/">
                                Return to Home
                            </Button>
                        </Group>
                    </Stack>
                </Paper>
            </Container>
        </div>
    );
}