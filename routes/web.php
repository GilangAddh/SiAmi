<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Home;
use App\Livewire\ManajemenPengguna;
use App\Livewire\StandarAudit;
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
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
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
    'role:ppm',
])->group(function () {
    Route::get('/manajemen-pengguna', ManajemenPengguna::class)->name('manajemen-pengguna');
    Route::get('/standar-audit', StandarAudit::class)->name('standar-audit');
});
