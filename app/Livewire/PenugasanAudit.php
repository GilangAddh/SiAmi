<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalAudit;

class PenugasanAudit extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $jadwalAudit = JadwalAudit::query()
            ->with(['periodeAudit', 'unitKerja', 'standarAudit'])
            ->paginate(10);

        return view('livewire.penugasan-audit', ['jadwalAudit' => $jadwalAudit])
            ->layout('components.layouts.app')
            ->title('Penugasan Audit');
    }
}
