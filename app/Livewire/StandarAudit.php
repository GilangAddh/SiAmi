<?php

namespace App\Livewire;

use App\Models\StandarAudit as ModelsStandarAudit;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;

class StandarAudit extends Component
{
    use WithPagination;

    public $title;
    public $search = '';

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    #[Validate('required|min:5')]
    public $nama_standar = '';

    #[Validate('required|min:5')]
    public $nomer_dokumen = '';

    #[Validate('required|min:5')]
    public $nomer_revisi = '';

    #[Validate('required|date')]
    public $tanggal_terbit = '';

    public function mount()
    {
        $this->title = "Standar Audit";
    }

    public function render()
    {
        $standar = ModelsStandarAudit::where('nama_standar', 'ilike', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.standar-audit', ['standar' => $standar]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Data Standar Audit';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit']);
    }

    public function saveData()
    {
        $this->validate();

        if ($this->modalAction === 'edit') {
            $user = ModelsStandarAudit::findOrFail($this->recordId);
            $user->update($this->only(['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit']));
        } else {
            ModelsStandarAudit::create(
                $this->only(['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit'])
            );
        }
        $this->resetModal();
    }

    private function loadRecordData()
    {
        $standar = ModelsStandarAudit::findOrFail($this->recordId);
        $this->nama_standar = $standar->nama_standar;
        $this->nomer_dokumen = $standar->nomer_dokumen;
        $this->nomer_revisi = $standar->nomer_revisi;
        $this->tanggal_terbit = $standar->tanggal_terbit;
    }

    public function delete()
    {
        $standar = ModelsStandarAudit::findOrFail($this->recordId);

        $standar->delete();
        $this->resetModal();
    }
}
