# Laravel Livewire Alpine Starter

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![Livewire](https://img.shields.io/badge/Livewire-3.x-4E64E9?style=for-the-badge&logo=livewire&logoColor=white)](https://livewire.laravel.com/)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com/)
[![DaisyUI](https://img.shields.io/badge/DaisyUI-4.x-5A0EF8?style=for-the-badge&logo=daisyui&logoColor=white)](https://daisyui.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16.x-336791?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org/)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev/)

![Laravel Starter Kit Screenshot](public/landing.png)

---

This starter kit provides a quick foundation for building Laravel apps, emphasizing a straightforward development experience with **Livewire** and **Alpine.js**. It uses **TailwindCSS 4** with **DaisyUI** for UI components, all bundled with **Vite**.

Unlike some other starter kits, because I'm opinonated against x-layout x-component this and that that the current official laravel starter kit comes with. Hundreds of files. This project intentionally avoids complex frontend frameworks like Flux and adheres to **traditional Blade layouts** using `@extends('layouts.app')` and `@section('content')` for simplicity and familiarity. Just go. Fast.

## Features

* **Laravel 12**: The latest version.
* **Livewire 3**: For extra dynamic stuff, or SEO stuff since dynamic things with alpine isnt.
* **Alpine.js**: You can do everything, everything, with Alpine, why complicate things with React?.
* **PostgreSQL**: Or Supabase.
* **TailwindCSS 4**: No brainer.
* **DaisyUI**: Makes things a bit cleaner and easier.
* **Vite**: Default.
* **Traditional Blade Layouts**: I've been using Laravel for too long.

---

## Getting Started

Setup reminder:

### Installation Steps

1.  **Clone the Repository:**
    ```bash
    git clone [https://github.com/your-username/laravel-livewire-alpine-starter.git](https://github.com/your-username/laravel-livewire-alpine-starter.git)
    cd laravel-livewire-alpine-starter
    ```

2.  **Environment Configuration:**
    ```bash
    cp .env.example .env
    ```

3.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

4.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```

5.  **Database Setup:**
    First create database.sqlite. Only switch out to pgSQL when you need to.
    ```bash
    php artisan migrate --seed
    ```

6.  **Frontend Dependencies:**
    ```bash
    npm install
    ```

7.  **Build Assets:**
    ```bash
    npm run build
    ```

Visit the site in your web browser. (I use Herd)

---

## Architecture Overview

---

## Code Quality & Development

### Code Formatting

To ensure consistent code style, use Laravel Pint:

```bash
./vendor/bin/pint