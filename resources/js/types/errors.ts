export interface ErrorPageProps {
    status: number;
    message: string;
    errorId: string;
    timestamp: string;
    canRetry: boolean;
    supportInfo: {
        email?: string;
        phone?: string;
        url?: string;
    };
    context?: {
        url?: string;
        userAgent?: string;
        ip?: string;
        userId?: number;
        method?: string;
        headers?: Record<string, string>;
    };
    debug?: {
        exception?: string;
        message?: string;
        file?: string;
        line?: number;
        trace?: Array<{
            file?: string;
            line?: number;
            function?: string;
            class?: string;
        }>;
    } | null;
}

export interface SupportInfo {
    email?: string;
    phone?: string;
    url?: string;
}

export interface ErrorContext {
    url?: string;
    userAgent?: string;
    ip?: string;
    userId?: number;
    method?: string;
    headers?: Record<string, string>;
}

export interface DebugInfo {
    exception?: string;
    message?: string;
    file?: string;
    line?: number;
    trace?: Array<{
        file?: string;
        line?: number;
        function?: string;
        class?: string;
    }>;
}