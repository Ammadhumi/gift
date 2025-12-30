<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GreetingController;
use App\Http\Controllers\TemplateController;
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

Route::get('/login', [AuthController::class, 'showLogin'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('login.perform');
Route::get('/register', [AuthController::class, 'showRegister'])->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register.perform');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->prefix('templates')->name('templates.')->group(function () {
    Route::get('/', [TemplateController::class, 'index'])->name('index');
    Route::get('/create', [TemplateController::class, 'create'])->name('create');
    Route::post('/', [TemplateController::class, 'store'])->name('store');
    Route::get('/{template}/edit', [TemplateController::class, 'edit'])->name('edit');
    Route::put('/{template}', [TemplateController::class, 'update'])->name('update');
    Route::delete('/{template}', [TemplateController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
});

Route::get('/', [GreetingController::class, 'create'])->middleware('auth')->name('greetings.create');
Route::post('/greetings', [GreetingController::class, 'store'])->middleware('auth')->name('greetings.store');
Route::get('/g/{greeting:uuid}/share', [GreetingController::class, 'share'])->middleware('auth')->name('greetings.share');
Route::get('/g/{greeting:uuid}', [GreetingController::class, 'intro'])->name('greetings.intro');
Route::get('/g/{greeting:uuid}/cake', [GreetingController::class, 'cake'])->name('greetings.cake');
Route::get('/g/{greeting:uuid}/wish', [GreetingController::class, 'wishForm'])->name('greetings.wish.form');
Route::post('/g/{greeting:uuid}/wish', [GreetingController::class, 'storeWish'])->name('greetings.wish');
Route::get('/g/{greeting:uuid}/album', [GreetingController::class, 'album'])->name('greetings.album');
Route::get('/g/{greeting:uuid}/final', [GreetingController::class, 'final'])->name('greetings.final');
