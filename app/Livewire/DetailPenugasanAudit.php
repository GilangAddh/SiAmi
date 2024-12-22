<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\PeriodeAudit;
use App\Models\PenugasanAudit;

class DetailPenugasanAudit extends Component
{
    use WithPagination;

    public $selectedAuditor = [];
    public $existingAuditor = [];

    public $periode;
    public $unitKerja;

    public function mount(PeriodeAudit $periode, User $unitKerja)
    {
        $this->periode = $periode;
        $this->unitKerja = $unitKerja;

        $this->existingAuditor = PenugasanAudit::where('id_unit',  $this->unitKerja->id)
            ->where('id_periode', $this->periode->id)
            ->pluck('id_auditor')
            ->toArray();

        $this->selectedAuditor = $this->existingAuditor;
    }

    public function render()
    {
        $auditor = User::where('is_active', true)->where('role', 'auditor')->orderBy('profile_name', 'asc')->get();

        return view('livewire.detail-penugasan-audit', ['auditor' => $auditor])->layout('components.layouts.app')->title('Penugasan Audit ' . $this->unitKerja->profile_name);
    }

    public function save()
    {
        if ($this->selectedAuditor == $this->existingAuditor) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Tidak ada perubahan penugasan audior."})');
            return;
        }

        PenugasanAudit::where('id_unit',  $this->unitKerja->id)
            ->where('id_periode', $this->periode->id)->delete();

        $data = array_map(function ($auditorId) {
            return [
                'id_unit' => $this->unitKerja->id,
                'id_periode' => $this->periode->id,
                'id_auditor' => $auditorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $this->selectedAuditor);

        PenugasanAudit::insert($data);

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Penugasan auditor berhasil disimpan."})');
    }
}
