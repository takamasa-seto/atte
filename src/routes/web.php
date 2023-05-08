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
Route::post('/', [AtteController::class, 'store']);
Route::get('/register', function() { return view('auth.register'); });
/* Route::get('/attendance', function() { return view('date'); }); */
Route::get('/attendance', [AtteController::class, 'show']);
Route::get('/user', [AtteController::class, 'user_list']);
Route::get('/user_attendance', [AtteController::class, 'user_attendance']);
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
