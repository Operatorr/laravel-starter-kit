### Code Quality
```bash
# Format code
./vendor/bin/pint               # Laravel Pint (PHP-CS-Fixer)

# Build assets
npm run build                   # Never run npm run build. I will build it myself.
npm run dev                     # Never run npm run dev. I will build it myself.

# Development server
php artisan serve               # Never Serve. I alsready serv the app with Herd
``` 

## Architecture Overview

### Core Technologies
- **Laravel 12** with **Livewire 3** for reactive components
- **PostgreSQL** as primary database
- **TailwindCSS 4** with **DaisyUI** for UI components
- **Alpine.js** for frontend interactivity
- **Vite** for asset bundling

### Frontend Architecture
- **Livewire 3** components for reactive UI
- **Alpine.js** for client-side interactivity
- **TailwindCSS 4** with **DaisyUI** for styling

## Key Configuration Files

### Frontend Build
- `vite.config.js` - Vite configuration with Livewire and Svelte plugins
- `tailwind.config.js` - TailwindCSS 4 configuration
- `resources/css/app.css` - Custom CSS and component overrides

### Laravel Configuration
- `composer.json` - PHP dependencies and development scripts