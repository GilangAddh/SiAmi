<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalAudit;
use App\Models\PeriodeAudit;

class PenugasanAudit extends Component
{
    use WithPagination;

    public $id_periode = "";
    public $sortStatus = "sudah";

    public function render()
    {
        $jadwalAudit = JadwalAudit::select('jadwal_audit.id_periode', 'jadwal_audit.id_unit')
            ->selectSub(function ($query) {
                $query->from('standar_audit')
                    ->selectRaw("STRING_AGG(DISTINCT standar_audit.nama_standar, '| ' ORDER BY standar_audit.nama_standar ASC)")
                    ->join('jadwal_audit as ja', 'standar_audit.id', '=', 'ja.id_standar')
                    ->whereColumn('ja.id_periode', 'jadwal_audit.id_periode')
                    ->whereColumn('ja.id_unit', 'jadwal_audit.id_unit');
            }, 'ordered_standards')
            ->selectSub(function ($query) {
                $query->from('penugasan_audit')
                    ->selectRaw('COUNT(DISTINCT penugasan_audit.id_auditor)')
                    ->whereColumn('penugasan_audit.id_unit', 'jadwal_audit.id_unit')
                    ->whereColumn('penugasan_audit.id_periode', 'jadwal_audit.id_periode');
            }, 'auditor_count')
            ->join('periode_audit', 'jadwal_audit.id_periode', '=', 'periode_audit.id')
            ->groupBy(
                'jadwal_audit.id_periode',
                'jadwal_audit.id_unit',
                'periode_audit.tanggal_mulai',
                'periode_audit.tanggal_akhir'
            )
            ->when($this->sortStatus, function ($query) {
                if ($this->sortStatus == "sudah") {
                    return $query->orderByRaw('CASE WHEN (SELECT COUNT(DISTINCT penugasan_audit.id_auditor) FROM penugasan_audit WHERE penugasan_audit.id_unit = jadwal_audit.id_unit AND penugasan_audit.id_periode = jadwal_audit.id_periode) > 0 THEN 1 ELSE 0 END DESC');
                } else {
                    return $query->orderByRaw('CASE WHEN (SELECT COUNT(DISTINCT penugasan_audit.id_auditor) FROM penugasan_audit WHERE penugasan_audit.id_unit = jadwal_audit.id_unit AND penugasan_audit.id_periode = jadwal_audit.id_periode) > 0 THEN 1 ELSE 0 END ASC');
                }
            })
            ->orderBy('periode_audit.tanggal_mulai', 'desc')
            ->orderBy('periode_audit.tanggal_akhir', 'desc')
            ->with(['periodeAudit', 'unitKerja', 'standarAudit'])
            ->when($this->id_periode, function ($query) {
                return $query->where('jadwal_audit.id_periode', $this->id_periode);
            })
            ->paginate(10);

        $periode = PeriodeAudit::where('is_active', true)->orderBy('tanggal_mulai', 'desc')
            ->orderBy('tanggal_akhir', 'desc')->get();

        return view('livewire.penugasan-audit', [
            'jadwalAudit' => $jadwalAudit,
            'periode' => $periode
        ])
            ->layout('components.layouts.app')
            ->title('Penugasan Audit');
    }
}
