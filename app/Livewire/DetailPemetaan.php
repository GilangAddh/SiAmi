<?php

namespace App\Livewire;

use App\Models\IndikatorStandarAudit;
use App\Models\PemetaanStandarAudit;
use App\Models\StandarAudit;
use App\Models\User;
use Livewire\Component;

class DetailPemetaan extends Component
{
    public $id_unit = '';
    public $profile_name = '';
    public $nama_standar = '';
    public $isModalOpen = false;
    public $modalTitle = '';
    public $recordId = null;
    public $indikator = [];
    public $selectedStandar = [];
    public $existingStandar = [];


    public function mount($id)
    {
        $this->id_unit = $id;
        $unit = User::findOrFail($this->id_unit);
        $this->profile_name = $unit->profile_name;

        $this->existingStandar = PemetaanStandarAudit::where('id_user', $this->id_unit)
            ->pluck('id_standar')
            ->toArray();

        $this->selectedStandar = $this->existingStandar;
    }

    public function render()
    {
        $standar = StandarAudit::where('is_active',  true)->get();
        $unit = User::where('id', '=', $this->id_unit);

        return view('livewire.detail-pemetaan', ['standar' => $standar, 'unit' => $unit]);
    }

    public function openModal($title, $recordId = null)
    {
        $this->resetModal();
        $this->modalTitle = "Indikator Standar {$title}";

        if ($recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->reset(['isModalOpen', 'modalTitle', 'recordId']);
    }

    private function loadRecordData()
    {
        $this->indikator = IndikatorStandarAudit::where('id_standar', $this->recordId)
            ->where('is_active', true)->get();
    }

    public function save()
    {
        if ($this->selectedStandar == $this->existingStandar) {
            session()->flash('message', 'Tidak ada standar yang dipilih.');
            return redirect()->route('pemetaan-standar-audit');
        }
        PemetaanStandarAudit::where('id_user', $this->id_unit)->delete();

        foreach ($this->selectedStandar as $standarId) {
            PemetaanStandarAudit::create([
                'id_user' => $this->id_unit,
                'id_standar' => $standarId,
            ]);
        }

        return redirect()->route('pemetaan-standar-audit')->with('success', 'Pemetaan berhasil disimpan.');
    }
}
