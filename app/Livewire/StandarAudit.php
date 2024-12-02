<?php

namespace App\Livewire;

use App\Livewire\Forms\StandarAuditForm;
use App\Models\StandarAudit as ModelsStandarAudit;
use Livewire\Component;
use Livewire\WithPagination;

class StandarAudit extends Component
{
    use WithPagination;

    public $title;
    public $search = '';

    public function mount()
    {
        $this->title = "Standar Audit";
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $standar = ModelsStandarAudit::where('nama_standar', 'ilike', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.standar-audit', ['standar' => $standar]);
    }

    public function delete($id)
    {
        $standar = ModelsStandarAudit::findOrFail($id);

        $standar->delete();
    }
}
