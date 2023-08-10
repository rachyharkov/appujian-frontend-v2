<?php

use App\Http\Controllers\ProgressUjianController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/sync-progress', [ProgressUjianController::class, 'store'])->name('api.sync-progress');
Route::post('/check-progress', [ProgressUjianController::class, 'show'])->name('api.check-progress');
