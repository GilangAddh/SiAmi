<?php

namespace App\Livewire;

use App\Models\PernyataanStandar;
use App\Models\JadwalAudit;
use App\Models\StandarAudit;
use App\Models\PeriodeAudit;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeskEvaluasi;

class DetailJadwalAudit extends Component
{
    use WithPagination;

    public $search = '';

    public $profile_name = '';

    public $id_unit;
    public $id_periode;
    public $id_standar;

    public $periode;

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';

    public $pernyataan = [];
    public $selectedPernyataan = [];
    public $existingPernyataan = [];

    public $sortStatus = "sudah";
    public $is_generated = false;

    public function mount(User $unitKerja, PeriodeAudit $periode)
    {
        $this->profile_name = $unitKerja->profile_name;
        $this->id_unit = $unitKerja->id;

        $this->periode = $periode;
        $this->id_periode = $periode->id;

        $this->is_generated = DeskEvaluasi::where('soft_unit', $unitKerja->id)
            ->where('soft_periode', $periode->id)
            ->exists();
    }

    public function render()
    {
        $standar = StandarAudit::select('standar_audit.nama_standar', 'standar_audit.id')
            ->selectSub(function ($query) {
                $query->from('jadwal_audit')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('jadwal_audit.id_standar', 'standar_audit.id')
                    ->where('jadwal_audit.id_unit', $this->id_unit)
                    ->where('jadwal_audit.id_periode', $this->id_periode);
            }, 'pernyataan_count')
            ->where('nama_standar', 'ilike', '%' . $this->search . '%')
            ->where('is_active', true)
            ->when($this->sortStatus, function ($query) {
                if ($this->sortStatus == "sudah") {
                    return $query->orderByRaw('CASE WHEN (SELECT COUNT(*) FROM jadwal_audit WHERE jadwal_audit.id_standar = standar_audit.id AND jadwal_audit.id_unit = ? AND jadwal_audit.id_periode = ?) > 0 THEN 1 ELSE 0 END DESC', [$this->id_unit, $this->id_periode]);
                } else {
                    return $query->orderByRaw('CASE WHEN (SELECT COUNT(*) FROM jadwal_audit WHERE jadwal_audit.id_standar = standar_audit.id AND jadwal_audit.id_unit = ? AND jadwal_audit.id_periode = ?) > 0 THEN 1 ELSE 0 END ASC', [$this->id_unit, $this->id_periode]);
                }
            })
            ->orderBy('nama_standar', 'asc')
            ->paginate(10);

        return view('livewire.detail-jadwal-audit', ['standar' => $standar])->layout('components.layouts.app')->title("Penjadwalan " . $this->profile_name);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function openModal($title, $recordId, $action)
    {
        $this->resetModal();
        $this->modalTitle = "Pernyataan Standar {$title}";
        $this->modalAction = $action;
        $this->id_standar = $recordId;

        $this->pernyataan = PernyataanStandar::where('id_standar', $recordId)
            ->where('is_active', true)->get();

        $this->existingPernyataan = JadwalAudit::where('id_unit', $this->id_unit)
            ->where('id_periode', $this->id_periode)
            ->where('id_standar', $recordId)
            ->pluck('id_pernyataan')
            ->toArray();

        $this->selectedPernyataan = $this->existingPernyataan;

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->reset(['isModalOpen', 'modalTitle', 'pernyataan', 'selectedPernyataan', 'existingPernyataan', 'modalAction']);
    }

    public function saveData()
    {
        if ($this->selectedPernyataan == $this->existingPernyataan) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Belum ada perubahan pernyataan yang digunakan."})');
            return;
        } else if ($this->selectedPernyataan == []) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Belum ada pernyataan yang dipilih."})');
            return;
        }

        $this->deleteData();

        $data = array_map(function ($pernyataanId) {
            return [
                'id_unit' => $this->id_unit,
                'id_periode' => $this->id_periode,
                'id_standar' => $this->id_standar,
                'id_pernyataan' => $pernyataanId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $this->selectedPernyataan);

        JadwalAudit::insert($data);

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Jadwal berhasil disimpan."})');
        $this->resetModal();
    }

    public function deleteData()
    {
        JadwalAudit::where('id_unit', $this->id_unit)
            ->where('id_periode', $this->id_periode)
            ->where('id_standar', $this->id_standar)
            ->delete();

        $this->reset(['search']);

        if ($this->modalAction == 'cancel') {
            $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Pembatalan standar berhasil disimpan."})');
            $this->resetModal();
        }
    }
}
