<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Home;
use App\Livewire\IndikatorStandarAudit;
use App\Livewire\KelolaPeran;
use App\Livewire\UnitKerja;
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
    'dynamic_role_access',
])->group(function () {
    Route::get('/dashboard', Home::class)->name('dashboard');
    Route::get('/unit-kerja', UnitKerja::class)->name('unit-kerja');
    Route::get('/kelola-peran', KelolaPeran::class)->name('kelola-peran');
    Route::get('/standar-audit', StandarAudit::class)->name('standar-audit');
    Route::get('/indikator-standar-audit/{id}', action: IndikatorStandarAudit::class)->name('indikator-standar-audit');
});
