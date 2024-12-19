<?php

namespace App\Livewire;

use App\Models\PernyataanStandar as ModelsPernyataanStandar;
use App\Models\StandarAudit;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class PernyataanStandar extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $subtitle;
    public $search;
    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $pertanyaan_standar = '';
    public $indikator_pertanyaan = '';
    public $bukti_objektif;
    public $new_bukti_objektif;
    public $id_standar = '';
    public $is_active = true;

    protected $rules = [
        'pertanyaan_standar' => 'required|min:5',
        'indikator_pertanyaan' => 'required',
        'is_active' => 'required'
    ];

    public function mount(StandarAudit $standarAudit)
    {
        $this->id_standar = $standarAudit->id;
        $this->subtitle = $standarAudit->nama_standar;
    }

    public function render()
    {
        $indikator = ModelsPernyataanStandar::where('id_standar', '=', $this->id_standar)
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pernyataan-standar', compact('indikator'))->layout('components.layouts.app')->title("Indikator $this->subtitle");
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Data Indikator ' . $this->subtitle;

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'pertanyaan_standar', 'indikator_pertanyaan', 'bukti_objektif', 'is_active']);
    }

    public function resetSearch()
    {
        $this->reset(['search']);
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
                $record = ModelsPernyataanStandar::findOrFail($this->recordId);
                $data = $this->only(['pertanyaan_standar', 'indikator_pertanyaan', 'id_standar', 'is_active']);

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

                ModelsPernyataanStandar::create(
                    array_merge(
                        $this->only(['pertanyaan_standar', 'indikator_pertanyaan', 'id_standar', 'is_active']),
                        [
                            'bukti_objektif' => $buktiObjektifPath,
                            'original_bukti_objektif' => $originalFileName
                        ]
                    )
                );
            }

            $this->resetModal();
            $this->resetSearch();
            $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data indikator berhasil disimpan."})');
        } catch (\Exception $e) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Data indikator gagal disimpan."})');
        }
    }

    private function loadRecordData()
    {
        $indikator = ModelsPernyataanStandar::findOrFail($this->recordId);
        $this->pertanyaan_standar = $indikator->pertanyaan_standar;
        $this->indikator_pertanyaan = $indikator->indikator_pertanyaan;
        $this->bukti_objektif = $indikator->bukti_objektif;
        $this->is_active = $indikator->is_active;
    }

    public function delete()
    {
        $indikator = ModelsPernyataanStandar::findOrFail($this->recordId);
        $filepath = 'public/' . $indikator->bukti_objektif;
        Storage::delete($filepath);
        $indikator->delete();
        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data indikator berhasil dihapus."})');
    }
}
