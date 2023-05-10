<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AtteController;
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

Route::get('/', [AtteController::class, 'create'])->middleware(['verified']);
Route::post('/', [AtteController::class, 'store'])->middleware(['verified']);
Route::get('/attendance', [AtteController::class, 'show'])->middleware(['verified']);
Route::get('/user', [AtteController::class, 'user_list'])->middleware(['verified']);
Route::get('/user_attendance', [AtteController::class, 'user_attendance'])->middleware(['verified']);

require __DIR__.'/auth.php';
