<?php

use App\Http\Controllers\HomeController;
use App\Livewire\Home;
use App\Livewire\ManajemenPengguna;
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

Route::get('/', function () {
    return view('index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', Home::class)->name('dashboard');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'role:ppm', // Middleware untuk role ppm
])->group(function () {
    Route::get('/manajemen-pengguna', ManajemenPengguna::class)->name('manajemen-pengguna');
});
