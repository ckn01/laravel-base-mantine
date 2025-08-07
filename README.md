# Laravel React Mantine Starter Kit

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel)](https://laravel.com)
[![React](https://img.shields.io/badge/React-18.x-61DAFB?style=for-the-badge&logo=react)](https://reactjs.org)
[![Mantine](https://img.shields.io/badge/Mantine-7.x-339AF0?style=for-the-badge&logo=mantine)](https://mantine.dev)
[![Inertia.js](https://img.shields.io/badge/Inertia.js-2.x-9553E9?style=for-the-badge&logo=inertia)](https://inertiajs.com)
[![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?style=for-the-badge&logo=typescript)](https://www.typescriptlang.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=for-the-badge)](https://opensource.org/licenses/MIT)

A modern, full-stack starter kit for building web applications with Laravel backend and React frontend, featuring Mantine UI components, Inertia.js for seamless SPA experience, and comprehensive development tools.

## ✨ Features

### 🎨 Frontend
- **React 18** with TypeScript for type-safe frontend development
- **Mantine UI** - Modern React components library with excellent theming
- **Inertia.js** - The modern monolith for building SPAs
- **Tailwind CSS** - Utility-first CSS framework
- **Dark/Light Theme** support with persistent user preferences
- **Responsive Design** - Mobile-first approach

### ⚡ Backend
- **Laravel 12** - Latest PHP framework with modern features
- **Filament Admin Panel** - Beautiful admin interface with role-based permissions
- **Spatie Permission** - Role and permission management
- **Authentication** - Complete auth system with email verification
- **Error Handling** - Comprehensive error pages and logging

### 🛠️ Development Tools
- **Vite** - Lightning fast build tool with HMR
- **TypeScript** - Type safety across the entire frontend
- **ESLint & Prettier** - Code linting and formatting
- **Vitest** - Modern testing framework for unit tests
- **Playwright** - End-to-end testing
- **Laravel Pint** - PHP code style fixer

### 📦 Additional Features
- **SSR Support** - Server-side rendering with Inertia.js
- **Ziggy** - Laravel routes in JavaScript
- **Database Seeding** - Pre-configured user roles and permissions
- **Error Pages** - Custom error pages for all HTTP status codes
- **Development Scripts** - Comprehensive npm scripts for all workflows

### Filament Plugin
- **Roles Permission**  - https://filamentphp.com/plugins/tharinda-rodrigo-spatie-roles-permissions

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Node.js 18 or higher
- Composer
- SQLite (default) or MySQL/PostgreSQL

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/ckn01/laravel-base-mantine.git
   cd laravel-base-mantine
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate --seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

### Development

Start the development servers:

```bash
# Terminal 1 - Laravel development server
php artisan serve

# Terminal 2 - Vite development server (HMR)
npm run dev
```

Visit `http://localhost:8000` to see your application.

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/      # Laravel controllers
│   ├── Models/               # Eloquent models
│   ├── Policies/            # Authorization policies
│   └── Providers/           # Service providers
├── resources/
│   ├── js/
│   │   ├── components/      # Reusable React components
│   │   ├── pages/          # Inertia.js pages
│   │   ├── hooks/          # Custom React hooks
│   │   └── types/          # TypeScript type definitions
│   └── css/               # Stylesheets
├── routes/
│   ├── web.php            # Web routes
│   └── auth.php           # Authentication routes
├── tests/
│   ├── Feature/           # Laravel feature tests
│   ├── Unit/             # Laravel unit tests
│   └── E2E/              # Playwright end-to-end tests
└── database/
    ├── migrations/        # Database migrations
    └── seeders/          # Database seeders
```

## 🎯 Available Scripts

### Frontend Development
```bash
npm run dev          # Start Vite dev server with HMR
npm run build        # Build for production
npm run build:ssr    # Build with SSR support
npm run preview      # Preview production build
```

### Code Quality
```bash
npm run lint         # Run ESLint
npm run format       # Format code with Prettier
npm run format:check # Check code formatting
npm run types        # TypeScript type checking
```

### Testing
```bash
npm run test         # Run unit tests with Vitest
npm run test:ui      # Run tests with UI
npm run test:coverage # Run tests with coverage
npm run test:e2e     # Run end-to-end tests
npm run test:all     # Run all tests
```

### Backend Development
```bash
php artisan serve    # Start Laravel development server
php artisan migrate  # Run database migrations
php artisan db:seed  # Seed the database
php artisan pint     # Format PHP code
```

## 🎨 Theming

The application supports both light and dark themes with persistent user preferences:

```typescript
// Custom hook for theme management
import { useAppearance } from '@/hooks/use-appearance';

function MyComponent() {
  const { colorScheme, toggleColorScheme } = useAppearance();
  
  return (
    <Button onClick={toggleColorScheme}>
      Switch to {colorScheme === 'dark' ? 'light' : 'dark'} mode
    </Button>
  );
}
```

## 🔐 Authentication & Authorization

### Default Users
After seeding, you'll have:
- **Admin User**: Access to Filament admin panel at `/admin-dashboard`
- **Regular User**: Standard application access

### Roles & Permissions
The application uses Spatie Laravel Permission with Filament integration:
- Role-based access control
- Permission management through admin panel
- Policy-based authorization

### Admin Panel
Access the Filament admin panel at `/admin-dashboard` with admin credentials to manage:
- Users and roles
- Permissions
- Application settings

## 🧪 Testing

### Unit Tests (Vitest)
```bash
npm run test                    # Run all unit tests
npm run test -- --watch        # Watch mode
npm run test:coverage          # Generate coverage report
```

### End-to-End Tests (Playwright)
```bash
npm run test:e2e               # Run E2E tests
npm run test:e2e:ui            # Run with Playwright UI
```

### PHP Tests (PHPUnit)
```bash
php artisan test               # Run Laravel tests
php artisan test --coverage   # Run with coverage
```

## 🐛 Error Handling

Comprehensive error handling system with:
- Custom error pages for all HTTP status codes (401, 403, 404, 419, 429, 500, 503)
- React error boundaries for frontend error recovery
- Detailed error logging and reporting
- User-friendly error messages

## 📦 Deployment

### Production Build
```bash
npm run build:ssr    # Build with SSR support
php artisan optimize # Optimize Laravel for production
```

### Environment Configuration
Update your `.env` file for production:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

## 🔧 Customization

### Adding New Components
1. Create components in `resources/js/components/`
2. Export from appropriate index files
3. Use TypeScript for type safety

### Adding New Pages
1. Create page components in `resources/js/pages/`
2. Add corresponding Laravel routes
3. Use Inertia.js helpers for navigation

### Styling
- Customize Mantine theme in `resources/js/theme.ts`
- Add Tailwind utilities as needed
- Use CSS modules for component-specific styles

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [React](https://reactjs.org) - A JavaScript library for building user interfaces
- [Mantine](https://mantine.dev) - React components library
- [Inertia.js](https://inertiajs.com) - The modern monolith
- [Filament](https://filamentphp.com) - Beautiful admin panels for Laravel
- [Spatie](https://spatie.be) - Laravel Permission package

## 📧 Support

If you have any questions or need help getting started, please open an issue on GitHub or contact the maintainers.

---

**Happy coding!** 🚀