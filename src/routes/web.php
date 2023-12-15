<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReminderController;

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

Route::get('/', function () {
    return redirect()->to('/login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::get('/reminder', [ReminderController::class, 'index'])->name('reminder.index');
Route::get('/reminder/create', [ReminderController::class, 'create'])->name('reminder.create');
Route::get('/reminder/edit', [ReminderController::class, 'edit'])->name('reminder.edit');
