<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReminderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// auth
Route::prefix('session')->name('session.')->group(
    function() {
        Route::post('/', [AuthController::class, 'login'])->name('login');
    }
);

// reminder
Route::middleware('auth:sanctum')->prefix('reminders')->name('reminders.')->group(
    function() {
        Route::get('/', [ReminderController::class, 'index'])->name('index');
    }
);