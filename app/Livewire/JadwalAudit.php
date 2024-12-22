<?php

namespace App\Livewire;

use App\Models\PeriodeAudit;
use Livewire\Component;
use Livewire\WithPagination;

class JadwalAudit extends Component
{
    use WithPagination;

    public $search_start = '';
    public $search_end = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetSearch()
    {
        $this->reset(['search_start', 'search_end']);
    }

    public function render()
    {
        $query = PeriodeAudit::select('periode_audit.*')
            ->selectSub(function ($subquery) {
                $subquery->from('jadwal_audit')
                    ->selectRaw('COUNT(DISTINCT id_unit)')
                    ->whereColumn('jadwal_audit.id_periode', 'periode_audit.id');
            }, 'unit_kerja_count')
            ->where('is_active', true)
            ->orderBy('unit_kerja_count', 'desc')
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('tanggal_akhir', 'desc');

        if (!empty($this->search_start)) {
            $query->where('tanggal_mulai', '>=', $this->search_start);
        }

        if (!empty($this->search_end)) {
            $query->where('tanggal_akhir', '<=', $this->search_end);
        }

        $periode = $query->paginate(10);

        return view('livewire.jadwal-audit', ['periode' => $periode])
            ->layout('components.layouts.app')
            ->title('Jadwal Audit');
    }
}
