<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/tasks-view', [TaskController::class, 'index'])->name('taskview');
    Route::post('/tasks-store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks-edit/{id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/tasks-update/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks-delete/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
});


