<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalAudit;

class PenugasanAudit extends Component
{
    use WithPagination;

    public function render()
    {
        $jadwalAudit = JadwalAudit::select('jadwal_audit.id_periode', 'jadwal_audit.id_unit')
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
            ->orderBy('auditor_count', 'desc')
            ->orderBy('periode_audit.tanggal_mulai', 'desc')
            ->orderBy('periode_audit.tanggal_akhir', 'desc')
            ->with(['periodeAudit', 'unitKerja', 'standarAudit'])
            ->paginate(10);


        return view('livewire.penugasan-audit', [
            'jadwalAudit' => $jadwalAudit,
        ])
            ->layout('components.layouts.app')
            ->title('Penugasan Audit');
    }
}
