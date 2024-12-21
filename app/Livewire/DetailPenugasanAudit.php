<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\PenugasanAudit as ModelsPenugasanAudit;

class DetailPenugasanAudit extends Component
{
    use WithPagination;

    public $profile_name = '';
    public $id_unit;

    public $selectedAuditor = [];
    public $existingAuditor = [];

    public function mount(User $unitKerja)
    {
        $this->profile_name = $unitKerja->profile_name;
        $this->id_unit = $unitKerja->id;

        $this->existingAuditor = ModelsPenugasanAudit::where('id_unit_kerja',  $unitKerja->id)
            ->pluck('id_auditor')
            ->toArray();

        $this->selectedAuditor = $this->existingAuditor;
    }

    public function render()
    {
        $auditor = User::where('is_active', true)->where('role', 'auditor')->orderBy('profile_name', 'asc')->get();

        return view('livewire.detail-penugasan-audit', ['auditor' => $auditor])->layout('components.layouts.app')->title('Penugasan Audit ' . $this->profile_name);
    }

    public function save()
    {
        if ($this->selectedAuditor == $this->existingAuditor) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Tidak ada auditor baru yang dipilih."})');
            return;
        }

        ModelsPenugasanAudit::where('id_unit_kerja', $this->id_unit)->delete();

        $data = array_map(function ($auditorId) {
            return [
                'id_unit_kerja' => $this->id_unit,
                'id_auditor' => $auditorId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $this->selectedAuditor);

        ModelsPenugasanAudit::insert($data);

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Penugasan auditor berhasil disimpan."})');

        return redirect()->route('penugasan-audit');
    }
}
