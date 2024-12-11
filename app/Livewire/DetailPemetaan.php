<?php

namespace App\Livewire;

use App\Models\StandarAudit;
use App\Models\User;
use Livewire\Component;

class DetailPemetaan extends Component
{
    public $id_unit = '';
    public $profile_name = '';
    public $isModalOpen = false;
    public $modalTitle = '';
    public $recordId = null;


    public function mount($id)
    {
        $this->id_unit = $id;
        $unit = User::findOrFail($this->id_unit);
        $this->profile_name = $unit->profile_name;
    }

    public function render()
    {
        $standar = StandarAudit::where('is_active',  true)->get();
        $unit = User::where('id', '=', $this->id_unit);

        return view('livewire.detail-pemetaan', ['standar' => $standar, 'unit' => $unit]);
    }
}
