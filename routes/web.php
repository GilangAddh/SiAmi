<?php

use App\Livewire\DetailPemetaan;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Home;
use App\Livewire\IndikatorStandarAudit;
use App\Livewire\KelolaPeran;
use App\Livewire\UnitKerja;
use App\Livewire\StandarAudit;
use App\Livewire\Auditor;
use App\Livewire\JadwalAudit;
use App\Livewire\PemetaanAuditor;
use App\Livewire\PemetaanStandarAudit;
use App\Livewire\PeriodeAudit;
use App\Livewire\PernyataanStandar;
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
    Route::get('/auditor', Auditor::class)->name('auditor');
    Route::get('/kelola-peran', KelolaPeran::class)->name('kelola-peran');

    Route::get('/standar-audit', StandarAudit::class)->name('standar-audit');
    Route::get('/pernyataan-standar/{standarAudit}', action: PernyataanStandar::class)->name('pernyataan-standar');

    Route::get('/periode-audit', PeriodeAudit::class)->name('periode-audit');

    Route::get('/pemetaan-standar-audit', PemetaanStandarAudit::class)->name('pemetaan-standar-audit');
    Route::get('/detail-pemetaan/{unitKerja}', action: DetailPemetaan::class)->name('detail-pemetaan');

    Route::get('/jadwal-audit', JadwalAudit::class)->name('jadwal-audit');
    Route::get('/pemetaan-auditor/{unitKerja}', action: PemetaanAuditor::class)->name('pemetaan-auditor');
});
