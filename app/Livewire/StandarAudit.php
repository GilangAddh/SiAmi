<?php

namespace App\Livewire;

use App\Models\StandarAudit as ModelsStandarAudit;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class StandarAudit extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $nama_standar = '';
    public $nomer_dokumen = '';
    public $nomer_revisi = '';
    public $tanggal_terbit = '';
    public $is_active = true;

    protected $rules = [
        'nama_standar' => 'required|min:3|max:255',
        'nomer_dokumen' => 'required|min:3|max:255',
        'nomer_revisi' => 'required',
        'tanggal_terbit' => 'required|text_date_format',
        'is_active' => 'required'
    ];

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
            unset($data[0][0]);

            $this->rows = array_map(function ($row) {
                return [
                    $row[0],
                    $row[1],
                    $row[2],
                    $this->convertDate($row[3]),
                ];
            }, $data[0]);
            $this->rows = array_filter($this->rows);
        }

        Storage::delete($path);
    }

    private function convertDate($date)
    {
        try {
            if (is_numeric($date)) {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))
                    ->format('Y-m-d');
            }
            return Carbon::createFromFormat('m/d/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }



    public function saveFromExcel()
    {
        $dataToInsert = [];

        foreach ($this->rows as $index => $row) {
            $messages = [
                // Standar Audit
                "rows.$index.0.required" => "Standar Audit wajib diisi.",
                "rows.$index.0.max" => "Standar Audit tidak boleh lebih dari 255 karakter.",
                "rows.$index.0.min" => "Standar audit tidak boleh kurang dari 3 karakter.",

                // Nomor Dokumen
                "rows.$index.1.required" => "Nomor Dokumen wajib diisi.",
                "rows.$index.1.max" => "Nomor Dokumen tidak boleh lebih dari 255 karakter.",
                "rows.$index.1.min" => "Nomor dokumen tidak boleh kurang dari 3 karakteer.",

                // Nomor Revisi
                "rows.$index.2.required" => "Nomor Revisi tidak boleh kosong",

                // Tanggal Terbit
                "rows.$index.3.required" => "Tanggal Terbit tidak boleh kosong.",
                "rows.$index.3.text_date_format" => "Tanggal Terbit harus berupa tanggal.",
            ];

            $this->validate([
                "rows.$index.0" => 'required|max:255|min:3',
                "rows.$index.1" => 'required|max:255|min:3',
                "rows.$index.2" => 'required',
                "rows.$index.3" => 'required|date_format:Y-m-d',
            ], $messages);

            $dataToInsert[] = [
                'nama_standar' => $row[0],
                'nomer_dokumen' => $row[1],
                'nomer_revisi' => $row[2],
                'tanggal_terbit' => $row[3],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        ModelsStandarAudit::insert($dataToInsert);

        $this->resetSearch();
        $this->resetModal();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data unit kerja berhasil disimpan."})');
    }

    public function deleteRow($index)
    {
        unset($this->rows[$index]);

        $this->rows = array_values($this->rows);
    }


    public function render()
    {
        $standar = ModelsStandarAudit::where('nama_standar', 'ilike', '%' . $this->search . '%')
            ->orWhere('nomer_dokumen', 'ilike', '%' . $this->search . '%')
            ->orWhere('nomer_revisi', 'ilike', '%' . $this->search . '%')
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.standar-audit', ['standar' => $standar])->layout('components.layouts.app')->title("Standar Audit");
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
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active']);
    }

    public function resetSearch()
    {
        $this->reset(['search']);
    }

    public function saveData()
    {
        $this->validate();

        $this->tanggal_terbit = Carbon::createFromFormat('j F Y', $this->tanggal_terbit)->format('Y-m-d');

        if ($this->modalAction === 'edit') {
            $user = ModelsStandarAudit::findOrFail($this->recordId);
            $user->update($this->only(['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active']));
        } else {
            ModelsStandarAudit::create(
                $this->only(['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active'])
            );
        }
        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data standar berhasil disimpan."})');
    }

    private function loadRecordData()
    {
        $standar = ModelsStandarAudit::findOrFail($this->recordId);
        $this->nama_standar = $standar->nama_standar;
        $this->nomer_dokumen = $standar->nomer_dokumen;
        $this->nomer_revisi = $standar->nomer_revisi;
        $this->tanggal_terbit = Carbon::parse($standar->tanggal_terbit)->translatedFormat('d F Y');
        $this->is_active = $standar->is_active;
    }

    public function delete()
    {
        $standar = ModelsStandarAudit::findOrFail($this->recordId);

        $standar->delete();
        $this->resetModal();
        $this->resetSearch();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data standar berhasil dihapus."})');
    }
}
