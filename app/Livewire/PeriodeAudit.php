<?php

namespace App\Livewire;

use App\Models\PeriodeAudit as ModelsPeriodeAudit;
use Carbon\Carbon;
use Livewire\Component;

class PeriodeAudit extends Component
{

    public $title;
    public $search_start = '';
    public $search_end = '';
    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $tanggal_mulai = '';
    public $tanggal_akhir = '';

    public $is_active = true;

    protected $rules = [
        'tanggal_mulai' => 'date|required',
        'tanggal_akhir' => 'date|required|after:tanggal_mulai',
        'is_active' => 'required',
    ];

    public function mount()
    {
        $this->title = 'Periode Audit';
    }

    public function render()
    {
        $periode = ModelsPeriodeAudit::orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.periode-audit', ['periode' => $periode]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . 'Data Periode Audit';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'tanggal_mulai', 'tanggal_akhir', 'is_active']);
    }
    public function saveData()
    {
        $this->tanggal_mulai = Carbon::createFromFormat('d/m/Y', $this->tanggal_mulai)->format('Y-m-d');
        $this->tanggal_akhir = Carbon::createFromFormat('d/m/Y', $this->tanggal_akhir)->format('Y-m-d');

        $this->validate();

        if ($this->modalAction === 'edit') {
            $user = ModelsPeriodeAudit::findOrFail($this->recordId);
            $user->update($this->only(['tanggal_mulai', 'tanggal_akhir', 'is_active']));
        } else {
            ModelsPeriodeAudit::create(
                $this->only(['tanggal_mulai', 'tanggal_akhir', 'is_active'])
            );
        }
        $this->resetModal();
    }

    private function loadRecordData()
    {
        $standar = ModelsPeriodeAudit::findOrFail($this->recordId);
        $this->tanggal_mulai = Carbon::parse($standar->tanggal_mulai)->format('d/m/Y');
        $this->tanggal_akhir = Carbon::parse($standar->tanggal_akhir)->format('d/m/Y');
        $this->is_active = $standar->is_active;
    }

    public function delete()
    {
        $standar = ModelsPeriodeAudit::findOrFail($this->recordId);

        $standar->delete();
        $this->resetModal();
    }
}
