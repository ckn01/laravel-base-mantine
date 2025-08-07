import '@testing-library/jest-dom';

// Global test setup
global.route = vi.fn((name: string, params?: any) => {
    const routes: Record<string, string> = {
        'dashboard': '/dashboard',
        'login': '/login',
        'register': '/register',
        'welcome': '/',
        'home': '/',
        'settings.profile': '/settings/profile',
        'settings.appearance': '/settings/appearance',
        'settings.password': '/settings/password',
    };
    
    if (params && typeof params === 'object') {
        return routes[name] || `/${name}`;
    }
    
    return routes[name] || `/${name}`;
});

// Mock window functions commonly used in error pages
Object.defineProperty(window, 'location', {
    value: {
        href: 'http://localhost:3000/test',
        reload: vi.fn()
    },
    writable: true
});

Object.defineProperty(window, 'history', {
    value: {
        back: vi.fn(),
        forward: vi.fn(),
        go: vi.fn(),
        pushState: vi.fn(),
        replaceState: vi.fn()
    },
    writable: true
});

// Mock ResizeObserver for Mantine components
global.ResizeObserver = vi.fn().mockImplementation(() => ({
    observe: vi.fn(),
    unobserve: vi.fn(),
    disconnect: vi.fn(),
}));

// Mock IntersectionObserver
global.IntersectionObserver = vi.fn().mockImplementation(() => ({
    observe: vi.fn(),
    unobserve: vi.fn(),
    disconnect: vi.fn(),
}));