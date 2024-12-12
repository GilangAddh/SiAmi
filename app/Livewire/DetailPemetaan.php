<?php

namespace App\Livewire;

use App\Models\IndikatorStandarAudit;
use App\Models\PemetaanStandarAudit;
use App\Models\StandarAudit;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DetailPemetaan extends Component
{
    use WithPagination;

    public $search = '';

    public $profile_name = '';
    public $id_unit;

    public $isModalOpen = false;
    public $modalTitle = '';

    public $indikator = [];
    public $selectedStandar = [];
    public $existingStandar = [];


    public function mount(User $unitKerja)
    {
        $this->profile_name = $unitKerja->profile_name;
        $this->id_unit = $unitKerja->id;

        $this->existingStandar = PemetaanStandarAudit::where('id_user',  $unitKerja->id)
            ->pluck('id_standar')
            ->toArray();

        $this->selectedStandar = $this->existingStandar;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $standar = StandarAudit::where('is_active',  true)->orderBy('created_at', 'desc')
            ->paginate(10);

        $standar = StandarAudit::where('is_active', true)
            ->where('nama_standar', 'ilike', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.detail-pemetaan', ['standar' => $standar])->layout('components.layouts.app')->title("Pemetaan Standar " . $this->profile_name);
    }

    public function openModal($title, $recordId = null)
    {
        $this->resetModal();
        $this->modalTitle = "Indikator Standar {$title}";

        if ($recordId) {
            $this->indikator = IndikatorStandarAudit::where('id_standar', $recordId)
                ->where('is_active', true)->get();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->reset(['isModalOpen', 'modalTitle']);
    }

    public function save()
    {
        if ($this->selectedStandar == $this->existingStandar) {
            session()->flash('message', 'Tidak ada standar yang dipilih.');
            return;
        }

        PemetaanStandarAudit::where('id_user', $this->id_unit)->delete();

        $data = array_map(function ($standarId) {
            return [
                'id_user' => $this->id_unit,
                'id_standar' => $standarId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $this->selectedStandar);

        PemetaanStandarAudit::insert($data);

        return redirect()->route('pemetaan-standar-audit')->with('success', 'Pemetaan berhasil disimpan.');
    }
}
