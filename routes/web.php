<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// SITE - FRENTE
Route::get('/', [App\Http\Controllers\Site\HomeController::class, 'index']);


// ADMIN - PAINEL
Auth::routes();

Route::prefix('painel')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin');
    
    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'index'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'authenticate']);

    Route::get('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'index'])->name('register');
    Route::post('/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);

    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);

    Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
    Route::put('profilesave', [App\Http\Controllers\Admin\ProfileController::class, 'save'])->name('profile.save');

    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::put('settingssave', [App\Http\Controllers\Admin\SettingController::class, 'save'])->name('settings.save');
});

Route::fallback([App\Http\Controllers\Site\PageController::class, 'index']);
