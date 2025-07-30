# Laravel Livewire Alpine Starter

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E64E9?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![DaisyUI](https://img.shields.io/badge/DaisyUI-4.x-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white)](https://daisyui.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16.x-336791?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)

---

This starter kit provides a robust foundation for building modern web applications with Laravel, emphasizing a straightforward development experience with **Livewire 3** for reactive components and **Alpine.js** for client-side interactivity. It leverages **TailwindCSS 4** with **DaisyUI** for beautiful UI components, all bundled efficiently with **Vite**.

Unlike some other starter kits, this project intentionally avoids complex frontend frameworks like Flux and adheres to **traditional Blade layouts** using `@extends('layouts.app')` and `@section('content')` for simplicity and familiarity.

## Features

* **Laravel 12**: The latest version of the popular PHP framework.
* **Livewire 3**: Build dynamic interfaces with the simplicity of PHP.
* **Alpine.js**: Add declarative JavaScript behavior directly in your HTML.
* **PostgreSQL**: A powerful and reliable relational database.
* **TailwindCSS 4**: A utility-first CSS framework for rapid styling.
* **DaisyUI**: A Tailwind CSS component library for ready-made UI elements.
* **Vite**: Next-generation frontend tooling for fast development.
* **Traditional Blade Layouts**: Simple and familiar view structure.

---

## Getting Started

This guide will help you set up and run the project on your local machine.

### Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/your-username/laravel-livewire-alpine-starter.git](https://github.com/your-username/laravel-livewire-alpine-starter.git)
    cd laravel-livewire-alpine-starter
    ```

2.  **Environment Configuration:**
    Create your environment file by copying the example. Remember to configure your PostgreSQL database credentials here.
    ```bash
    cp .env.example .env
    ```

3.  **Install PHP Dependencies:**
    Install all required PHP packages using Composer.
    ```bash
    composer install
    ```

4.  **Generate Application Key:**
    Generate a unique application key for security purposes.
    ```bash
    php artisan key:generate
    ```

5.  **Database Setup:**
    Run database migrations to create your table schema and seed the database with any initial data.
    ```bash
    php artisan migrate --seed
    ```

6.  **Frontend Dependencies:**
    Install Node.js packages required for frontend asset compilation.
    ```bash
    npm install
    ```

7.  **Build Assets:**
    Compile your frontend assets (CSS, JavaScript).
    ```bash
    npm run build
    ```

After completing these steps, you can visit the site in your web browser.

---

## Architecture Overview

### Core Technologies

* **Laravel 12** with **Livewire 3** for reactive components
* **PostgreSQL** as primary database
* **TailwindCSS 4** with **DaisyUI** for UI components
* **Alpine.js** for frontend interactivity
* **Vite** for asset bundling

### Frontend Architecture

* **Livewire 3** components for reactive UI
* **Alpine.js** for client-side interactivity
* **TailwindCSS 4** with **DaisyUI** for styling

---

## Code Quality & Development

### Code Formatting

To ensure consistent code style, use Laravel Pint:

```bash
./vendor/bin/pint