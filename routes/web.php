<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RekomendasiLombaController;
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

Route::pattern('id', '[0-9]+'); // artinya ketika ada parameter (id), maka harus berupa angka

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');

Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register', [AuthController::class, 'postregister']);

Route::middleware(['auth'])->group(function () { // artinya semua route di dalam group ini harus login dulu
// jangan lupa nanti dimodifikasi sesusai dengan kebutuhan, terus kasih comment kalau sekiranya butuh
    Route::get('/', [WelcomeController::class, 'index']);
    Route::get('/rekomendasi', [RekomendasiLombaController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/{id}', [RekomendasiLombaController::class, 'updateStatus'])->name('rekomendasi.updateStatus');

    // Route::get('/login', function () {
//     return view('auth.login');
// });
// Route::get('/register', function () {
//     return view('auth.register');
// });

});
