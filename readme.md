# Getting Started

This guide will help you set up and run the project on your local machine.

### Installation Steps

1.  **Environment Configuration:**
    Create your environment file by copying the example:
    ```bash
    cp .env.example .env
    ```
2.  **PHP Dependencies:**
    Install all required PHP packages:
    ```bash
    composer install
    ```
3.  **Application Key:**
    Generate a unique application key for security:
    ```bash
    php artisan key:generate
    ```
4.  **Database Setup:**
    Run database migrations and seed the database with initial data:
    ```bash
    php artisan migrate --seed
    ```
5.  **Frontend Dependencies:**
    Install Node.js packages for frontend assets:
    ```bash
    npm install
    ```
6.  **Build Assets:**
    Compile your frontend assets (CSS, JavaScript):
    ```bash
    npm run build
    ```

After completing these steps, you can visit the site in your web browser.