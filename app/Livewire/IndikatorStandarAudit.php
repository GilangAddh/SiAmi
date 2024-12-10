<?php

namespace App\Livewire;

use App\Models\IndikatorStandarAudit as ModelsIndikatorStandarAudit;
use App\Models\StandarAudit;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class IndikatorStandarAudit extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $title;
    public $subtitle;
    public $search;
    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;


    public $nomer_pertanyaan_standar = '';
    public $pertanyaan_standar = '';
    public $indikator_pertanyaan = '';
    public $bukti_objektif;
    public $new_bukti_objektif;
    public $id_standar = '';
    public $is_active = true;

    protected $rules = [
        'nomer_pertanyaan_standar' => 'required',
        'pertanyaan_standar' => 'required|min:5',
        'indikator_pertanyaan' => 'required',
        'id_standar' => 'required',
        'is_active' => 'required'
    ];

    public function mount($id)
    {
        $this->id_standar = $id;
        $standar = StandarAudit::findOrFail($this->id_standar);
        $this->subtitle = $standar->nama_standar;
        $this->title = "Indikator $this->subtitle";
    }

    public function render()
    {
        $standar = StandarAudit::all();
        $indikator = ModelsIndikatorStandarAudit::where('id_standar', '=', $this->id_standar)
            ->where('nomer_pertanyaan_standar', 'ilike', '%' . $this->search . '%')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.indikator-standar-audit', [
            'indikator' => $indikator,
            'standar' => $standar
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Data Indikator Standar Audit';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nomer_pertanyaan_standar', 'pertanyaan_standar', 'indikator_pertanyaan', 'bukti_objektif', 'is_active']);
    }

    public function saveData()
    {
        if ($this->modalAction == 'tambah') {
            $this->rules['bukti_objektif'] = 'required|file|mimes:pdf|max:2048';
        }
        $this->validate();
        try {

            if ($this->modalAction === 'edit') {
                $buktiObjektifPath = null;
                $originalFileName = null;

                if ($this->new_bukti_objektif) {
                    $buktiObjektifPath = $this->new_bukti_objektif->store('bukti_objektif', 'public');
                    $originalFileName = $this->new_bukti_objektif->getClientOriginalName();
                }
                $record = ModelsIndikatorStandarAudit::findOrFail($this->recordId);
                $data = $this->only(['nomer_pertanyaan_standar', 'pertanyaan_standar', 'indikator_pertanyaan', 'id_standar', 'is_active']);

                // Perbarui path file jika file baru diunggah
                if ($buktiObjektifPath) {
                    $data['bukti_objektif'] = $buktiObjektifPath;
                    $data['original_bukti_objektif'] = $originalFileName;
                }

                $record->update($data);
            } else {
                $buktiObjektifPath = null;
                $originalFileName = null;

                if ($this->bukti_objektif) {
                    $buktiObjektifPath = $this->bukti_objektif->store('bukti_objektif', 'public');
                    $originalFileName = $this->bukti_objektif->getClientOriginalName();
                }

                ModelsIndikatorStandarAudit::create(
                    array_merge(
                        $this->only(['nomer_pertanyaan_standar', 'pertanyaan_standar', 'indikator_pertanyaan', 'id_standar', 'is_active']),
                        [
                            'bukti_objektif' => $buktiObjektifPath,
                            'original_bukti_objektif' => $originalFileName
                        ]
                    )
                );
            }

            $this->resetModal(); // Reset modal setelah berhasil menyimpan
            session()->flash('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    private function loadRecordData()
    {
        $indikator = ModelsIndikatorStandarAudit::findOrFail($this->recordId);
        $this->nomer_pertanyaan_standar = $indikator->nomer_pertanyaan_standar;
        $this->pertanyaan_standar = $indikator->pertanyaan_standar;
        $this->indikator_pertanyaan = $indikator->indikator_pertanyaan;
        $this->bukti_objektif = $indikator->bukti_objektif;
        $this->id_standar = $indikator->id_standar;
        $this->is_active = $indikator->is_active;
    }

    public function delete()
    {
        $indikator = ModelsIndikatorStandarAudit::findOrFail($this->recordId);
        $filepath = 'public/' . $indikator->bukti_objektif;
        Storage::delete($filepath);
        $indikator->delete();
        $this->resetModal();
    }
}
