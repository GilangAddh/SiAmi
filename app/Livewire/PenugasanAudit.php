<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalAudit;
use App\Models\PeriodeAudit;
use App\Models\DeskEvaluasi;
use App\Models\DetailDeskEvaluasi;
use App\Models\PenugasanAudit as ModelsPenugasanAudit;

class PenugasanAudit extends Component
{
    use WithPagination;

    public $id_periode = "";
    public $sortStatus = "sudah";
    public $isModalOpen = false;

    public $unit;
    public $concat_periode;
    public $soft_unit;
    public $soft_periode;

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
            ->selectSub(function ($query) {
                $query->from('desk_evaluasi')
                    ->selectRaw('CASE WHEN COUNT(*) > 0 THEN true ELSE false END')
                    ->whereColumn('desk_evaluasi.soft_periode', 'jadwal_audit.id_periode')
                    ->whereColumn('desk_evaluasi.soft_unit', 'jadwal_audit.id_unit');
            }, 'is_generated')
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

    public function openModal($unit, $concat_periode, $soft_unit, $soft_periode)
    {
        $this->unit = $unit;
        $this->concat_periode = $concat_periode;
        $this->soft_unit = $soft_unit;
        $this->soft_periode = $soft_periode;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->reset(['unit', 'concat_periode', 'soft_unit', 'soft_periode', 'isModalOpen']);
    }

    public function generate()
    {
        $jadwalAudit = JadwalAudit::with([
            'periodeAudit',
            'unitKerja',
            'standarAudit',
            'pernyataanStandar',
        ])
            ->where('id_unit', $this->soft_unit)
            ->where('id_periode', $this->soft_periode)
            ->get();

        $groupedJadwals = $jadwalAudit->groupBy(fn($jadwal) => $jadwal->id_unit . '-' . $jadwal->id_periode . '-' . $jadwal->id_standar);
        $auditors = ModelsPenugasanAudit::where('id_unit', $this->soft_unit)
            ->where('id_periode', $this->soft_periode)
            ->with('auditor')
            ->get();

        $auditorNames = $auditors->pluck('auditor.profile_name')->filter()->toArray();
        $auditorIds = $auditors->pluck('auditor.id')->filter()->toArray();

        $groupedJadwals->each(function ($group, $key) use ($auditorNames, $auditorIds) {
            $uniqueJadwal = $group->first();

            $desk = DeskEvaluasi::create([
                'hard_periode_awal' => $uniqueJadwal->periodeAudit->tanggal_mulai,
                'hard_periode_akhir' => $uniqueJadwal->periodeAudit->tanggal_akhir,
                'hard_unit' => $uniqueJadwal->unitKerja->profile_name,
                'hard_standar' => $uniqueJadwal->standarAudit->nama_standar,
                'hard_auditor' => $auditorNames,
                'soft_periode' => $uniqueJadwal->periodeAudit->id,
                'soft_unit' => $uniqueJadwal->unitKerja->id,
                'soft_standar' => $uniqueJadwal->standarAudit->id,
                'soft_auditor' => $auditorIds,
                'status' => 'Pengisian Evaluasi',
            ]);

            $relevantPernyataan = $group->pluck('pernyataanStandar')->flatten()->filter(function ($pernyataan) use ($desk) {
                return $pernyataan->id_standar == $desk->soft_standar;
            });

            $relevantPernyataan->each(function ($pernyataan) use ($desk) {
                DetailDeskEvaluasi::create([
                    'id_desk' => $desk->id,
                    'id_pernyataan' => $pernyataan->id,
                    'pernyataan' => $pernyataan->pernyataan_standar,
                    'indikator' => $pernyataan->indikator_pertanyaan,
                    'pertanyaan' => $pernyataan->pertanyaan,
                    'auditee' => $pernyataan->auditee,
                    'bukti_objektif' => $pernyataan->bukti_objektif,
                ]);
            });
        });

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Desk Evaluasi berhasil disimpan."})');
        $this->redirect('/penugasan-audit', navigate: true);
    }
}
