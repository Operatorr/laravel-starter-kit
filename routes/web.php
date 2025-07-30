<?php

use App\Http\Controllers\ChatStreamController;
use App\Http\Controllers\ThemeController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', function () {
    if (! Auth::check()) {
        $user = User::first();
        if ($user) {
            Auth::login($user);
        }
    }

    return view('home');
})->name('home');

Route::post('/chat/stream', [ChatStreamController::class, 'stream'])->name('chat.stream');
Route::get('/chat', [ChatStreamController::class, 'chat'])->name('chat.test');

// Theme routes
Route::post('/theme/update', [ThemeController::class, 'update'])->name('theme.update');
Route::get('/theme', [ThemeController::class, 'show'])->name('theme.show');

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
