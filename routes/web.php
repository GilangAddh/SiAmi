<?php

use Illuminate\Support\Facades\Auth;
use App\Livewire\Home;
use App\Livewire\KelolaPeran;
use App\Livewire\UnitKerja;
use App\Livewire\StandarAudit;
use App\Livewire\Auditor;
use App\Livewire\DeskEvaluasi;
use App\Livewire\DetailDeskEvaluasi;
use App\Livewire\DetailJadwalAudit;
use App\Livewire\DetailPenugasanAudit;
use App\Livewire\JadwalAudit;
use App\Livewire\JadwalUnitKerja;
use App\Livewire\PenugasanAudit;
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
    Route::get('/kelola-peran', KelolaPeran::class)->name('kelola-peran');

    Route::get('/standar-audit', StandarAudit::class)->name('standar-audit');
    Route::get('/pernyataan-standar/{standarAudit}', action: PernyataanStandar::class)->name('pernyataan-standar');
    Route::get('/unit-kerja', UnitKerja::class)->name('unit-kerja');
    Route::get('/auditor', Auditor::class)->name('auditor');
    Route::get('/periode-audit', PeriodeAudit::class)->name('periode-audit');

    Route::get('/jadwal-audit', JadwalAudit::class)->name('jadwal-audit');
    Route::get('/jadwal-unit-kerja/{periode}', action: JadwalUnitKerja::class)->name('jadwal-unit-kerja');
    Route::get('/detail-jadwal-audit/{periode}/{unitKerja}', action: DetailJadwalAudit::class)->name('detail-jadwal-audit');

    Route::get('/penugasan-audit', PenugasanAudit::class)->name('penugasan-audit');
    Route::get('/detail-penugasan-audit/{periode}/{unitKerja}', action: DetailPenugasanAudit::class)->name('detail-penugasan-audit');

    Route::get('/desk-evaluasi', DeskEvaluasi::class)->name('desk-evaluasi');
    Route::get('/detail-desk-evaluasi/{desk}/{action}', action: DetailDeskEvaluasi::class)->name('detail-desk-evaluasi');
});
