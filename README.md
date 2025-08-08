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

### 🔌 Filament Plugins
- **Roles Permission** - https://filamentphp.com/plugins/tharinda-rodrigo-spatie-roles-permissions
- **Spatie Laravel Backup** - https://filamentphp.com/plugins/shuvroroy-spatie-laravel-backup
- **Spatie Laravel Health** - https://filamentphp.com/plugins/shuvroroy-spatie-laravel-health
- **Activity Log** - User activity tracking and audit logs
- **Jobs Monitor** - Background job monitoring and management


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

## 📋 Project Management dengan Backlog.md

Proyek ini menggunakan [Backlog.md](https://github.com/MrLesk/Backlog.md) untuk manajemen tugas dan kolaborasi antara developer dan AI agents. Backlog.md adalah tool markdown-native yang mengubah repository Git menjadi project board yang self-contained.

### 🚀 Instalasi Backlog.md

```bash
# Menggunakan npm
npm i -g backlog.md

# Menggunakan bun
bun add -g backlog.md

# Menggunakan Homebrew (macOS)
brew install backlog-md

# Menggunakan Nix
nix run github:MrLesk/Backlog.md
```

### 📝 Penggunaan Dasar

#### Inisialisasi Project
```bash
# Inisialisasi backlog untuk project yang sudah ada
backlog init

# Atau buat project baru dengan backlog
backlog init "My Awesome Project"
```

#### Manajemen Task
```bash
# Membuat task baru
backlog task create "Implementasi fitur login"

# Membuat task dengan deskripsi
backlog task create "Setup authentication" -d "Implementasi sistem autentikasi dengan Laravel Sanctum"

# Membuat task dengan assignee
backlog task create "Fix bug navbar" -a @developer

# Membuat task dengan status dan label
backlog task create "Refactor components" -s "In Progress" -l frontend,react

# Membuat task dengan prioritas
backlog task create "Security update" --priority high

# Membuat subtask
backlog task create -p 14 "Add Google OAuth integration"
```

#### Melihat dan Mengelola Tasks
```bash
# Melihat semua tasks
backlog task list

# Filter berdasarkan status
backlog task list -s "To Do"

# Filter berdasarkan assignee
backlog task list -a @developer

# Melihat detail task
backlog task 7

# Edit task
backlog task edit 7 -a @sara -l auth,backend
```

#### Kanban Board
```bash
# Melihat board di terminal
backlog board view

# Export board ke markdown
backlog board export

# Membuka web interface
backlog browser

# Custom port untuk web interface
backlog browser --port 8080
```

### 🤖 Kolaborasi dengan AI Agents

Backlog.md dirancang khusus untuk kolaborasi dengan AI agents seperti Claude, Gemini, atau Codex:

```bash
# Contoh instruksi untuk Claude
"Claude, please create tasks for implementing a search functionality that includes:
- Task search
- Documentation search  
- Decision search
Please create relevant tasks to tackle this request."

# Assign tasks ke AI
"Claude please implement all tasks related to the web search functionality (task-10, task-11, task-12)
- before starting to write code use 'ultrathink mode' to prepare an implementation plan
- use multiple sub-agents when possible and dependencies allow"
```

### 📁 Struktur Backlog

Semua data backlog disimpan dalam folder `backlog/` sebagai file Markdown yang human-readable:

```
backlog/
├── config.yml              # Konfigurasi project
├── tasks/                   # Active tasks
│   ├── task-001 - Setup authentication.md
│   └── task-002 - Create dashboard.md
├── completed/               # Completed tasks
├── archive/                 # Archived items
│   ├── tasks/
│   └── drafts/
├── docs/                    # Project documentation
├── decisions/               # Architecture decisions
└── drafts/                  # Draft tasks and ideas
```

### 🌐 Web Interface

Backlog.md menyediakan web interface modern untuk manajemen visual:

- **Interactive Kanban board** dengan drag-and-drop
- **Task creation dan editing** dengan form validation
- **Real-time updates** saat mengelola tasks
- **Responsive design** untuk desktop dan mobile
- **Archive tasks** dengan dialog konfirmasi
- **Seamless CLI integration** - perubahan tersinkronisasi dengan file markdown

### 💡 Tips Penggunaan

1. **Gunakan labels** untuk kategorisasi (frontend, backend, bug, feature)
2. **Set prioritas** untuk tasks penting (high, medium, low)
3. **Buat subtasks** untuk breakdown work yang kompleks
4. **Gunakan dependencies** untuk menunjukkan task yang saling bergantung
5. **Manfaatkan AI collaboration** untuk planning dan implementation
6. **Export board** secara berkala untuk reporting

### 🔗 Integrasi dengan Development Workflow

Backlog.md terintegrasi sempurna dengan workflow development:

- **Git integration** - semua perubahan dapat di-commit
- **Branch-based work** - tasks dapat dikaitkan dengan branches
- **CI/CD friendly** - dapat diintegrasikan dengan pipeline
- **Team collaboration** - sharing melalui Git repository
- **AI-ready** - siap untuk kolaborasi dengan AI coding assistants

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