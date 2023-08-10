<?php

use App\Http\Controllers\MasterData\AnotherPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterData\UserController;
use App\Http\Controllers\UjianController;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/mengerjakan', [UjianController::class, 'index'])->name('mengerjakan');
    Route::post('/selesai', [UjianController::class, 'finish_exam'])->name('finish-exam');
});
