<?php

namespace App\Livewire;

use App\Models\PernyataanStandar as ModelsPernyataanStandar;
use App\Models\StandarAudit;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    public $pernyataan_standar = '';
    public $pertanyaan = [];
    public $indikator_pertanyaan = [];
    public $bukti_objektif = [];
    public $new_bukti_objektif = [];
    public $auditee = [];
    public $id_standar = '';
    public $is_active = true;

    protected $rules = [
        'pernyataan_standar' => 'required|min:5',
        'is_active' => 'required',
        'pertanyaan' => 'nullable|array',
        'pertanyaan.*' => 'required|string',
        'indikator_pertanyaan' => 'nullable|array',
        'indikator_pertanyaan.*' => 'required|string',
        'bukti_objektif' => 'nullable|array',
        'bukti_objektif.*' => 'required|string',
        'auditee' => 'nullable|array',
        'auditee.*' => 'required|string'
    ];

    // protected $messages = [
    //     'pertanyaan.*.required' => 'Pertanyaan :index tidak boleh kosong.',
    // ];

    public $file;
    public $rows = [];

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $this->processFile();
    }

    public function processFile()
    {
        $path = $this->file->store('temp');
        $data = Excel::toArray([], Storage::path($path));

        if (!empty($data)) {
            $sheetData = $data[0];

            unset($sheetData[0], $sheetData[2]);

            $sheetData = array_values($sheetData);

            $this->rows = array_map(function ($row) {
                return array_filter([
                    $row[0] ?? null,
                    $row[1] ?? null,
                    $row[2] ?? null,
                    $row[3] ?? null,
                ]);
            }, $sheetData);

            $this->rows = array_filter($this->rows, fn($row) => !empty($row));
        }

        Storage::delete($path);

        if (!empty($this->rows)) {
            $this->pernyataan_standar = $this->rows[0][0] ?? null;
            unset($this->rows[0]);

            $this->indikator_pertanyaan = array_map(fn($item) => $item[0] ?? null, $this->rows);
            $this->pertanyaan = array_map(fn($item) => $item[1] ?? null, $this->rows);
            $this->bukti_objektif = array_map(fn($item) => $item[2] ?? null, $this->rows);
            $this->auditee = array_map(fn($item) => $item[3] ?? null, $this->rows);
        } else {
            $this->pernyataan_standar = '';
            $this->indikator_pertanyaan = [];
            $this->pertanyaan = [];
            $this->bukti_objektif = [];
            $this->auditee = [];
        }
    }


    public function deleteRow($index)
    {
        unset($this->rows[$index]);

        $this->rows = array_values($this->rows);
    }



    public function mount(StandarAudit $standarAudit)
    {
        $this->id_standar = $standarAudit->id;
        $this->subtitle = $standarAudit->nama_standar;
    }

    public function render()
    {
        $indikator = ModelsPernyataanStandar::where('id_standar', '=', $this->id_standar)
            ->where('pernyataan_standar', 'ilike', '%' . $this->search . '%')
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
        $this->modalTitle = ucfirst($action) . ' Data pernyataan ' . $this->subtitle;

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'pernyataan_standar', 'pertanyaan', 'indikator_pertanyaan', 'bukti_objektif', 'is_active', 'auditee', 'rows']);
    }

    public function resetSearch()
    {
        $this->reset(['search']);
    }

    public function saveData()
    {
        $this->validate();
        try {

            if ($this->modalAction === 'edit') {
                $record = ModelsPernyataanStandar::findOrFail($this->recordId);

                $record->update([
                    'pernyataan_standar' => $this->pernyataan_standar,
                    'indikator_pertanyaan' => $this->indikator_pertanyaan,
                    'pertanyaan' => $this->pertanyaan,
                    'id_standar' => $this->id_standar,
                    'bukti_objektif' => $this->bukti_objektif,
                    'auditee' => $this->auditee,
                    'is_active' => $this->is_active,
                ]);
            } else {
                ModelsPernyataanStandar::create([
                    'pernyataan_standar' => $this->pernyataan_standar,
                    'indikator_pertanyaan' => $this->indikator_pertanyaan,
                    'pertanyaan' => $this->pertanyaan,
                    'id_standar' => $this->id_standar,
                    'bukti_objektif' => $this->bukti_objektif,
                    'auditee' => $this->auditee,
                    'is_active' => $this->is_active,
                ]);
            }

            $this->resetModal();
            $this->resetSearch();
            $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data pernyataan berhasil disimpan."})');
        } catch (\Exception $e) {
            $this->js('SwalGlobal.fire({icon: "error", title: "Gagal", text: "Data pernyataan gagal disimpan."})');
        }
    }

    private function loadRecordData()
    {
        $indikator = ModelsPernyataanStandar::findOrFail($this->recordId);

        $this->pernyataan_standar = $indikator->pernyataan_standar;
        $this->pertanyaan = $indikator->pertanyaan;
        $this->indikator_pertanyaan = $indikator->indikator_pertanyaan;
        $this->bukti_objektif = $indikator->bukti_objektif;
        $this->auditee = $indikator->auditee;
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

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data pernyataan berhasil dihapus."})');
    }

    public function addIndikator()
    {
        $this->indikator_pertanyaan[] = '';
    }

    public function deleteIndikator($index)
    {
        unset($this->indikator_pertanyaan[$index]);
        $this->indikator_pertanyaan = array_values($this->indikator_pertanyaan);
    }

    public function addPertanyaan()
    {
        $this->pertanyaan[] = '';
    }

    public function deletePertanyaan($index)
    {
        unset($this->pertanyaan[$index]);
        $this->pertanyaan = array_values($this->pertanyaan);
    }

    public function addBukti()
    {
        $this->bukti_objektif[] = '';
    }

    public function deleteBukti($index)
    {
        unset($this->bukti_objektif[$index]);
        $this->bukti_objektif = array_values($this->bukti_objektif);
    }

    public function addAuditee()
    {
        $this->auditee[] = '';
    }

    public function deleteAuditee($index)
    {
        unset($this->auditee[$index]);
        $this->auditee = array_values($this->auditee);
    }
}
