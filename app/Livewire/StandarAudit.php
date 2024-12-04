<?php

namespace App\Livewire;

use App\Models\StandarAudit as ModelsStandarAudit;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class StandarAudit extends Component
{
    use WithPagination;

    public $title;
    public $search = '';

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $nama_standar = '';
    public $nomer_dokumen = '';
    public $nomer_revisi = '';
    public $tanggal_terbit = '';

    protected $rules = [
        'nama_standar' => 'required|min:5|max:255',
        'nomer_dokumen' => 'required|min:5|max:255',
        'nomer_revisi' => 'required|min:5|max:255',
        'tanggal_terbit' => 'required|date',
    ];

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

    public function indikatorStandar($id)
    {
        $this->redirect("/indikator-standar-audit/$id");
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
        $this->tanggal_terbit = Carbon::createFromFormat('d/m/Y', $this->tanggal_terbit)->format('Y-m-d');

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
        $this->tanggal_terbit = Carbon::parse($standar->tanggal_terbit)->format('d/m/Y');
    }

    public function delete()
    {
        $standar = ModelsStandarAudit::findOrFail($this->recordId);

        $standar->delete();
        $this->resetModal();
    }
}
