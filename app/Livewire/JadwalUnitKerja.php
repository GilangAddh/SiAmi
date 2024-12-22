<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\PeriodeAudit;
use App\Models\User;

class JadwalUnitKerja extends Component
{
    use WithPagination;

    public $periode;
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function mount(PeriodeAudit $periode)
    {
        $this->periode = $periode;
    }

    public function render()
    {
        $unit = User::select('users.profile_name', 'users.id')
            ->selectSub(function ($query) {
                $query->from('jadwal_audit')
                    ->selectRaw('COUNT(DISTINCT jadwal_audit.id_standar)')
                    ->whereColumn('jadwal_audit.id_unit', 'users.id')
                    ->where('jadwal_audit.id_periode', $this->periode->id);
            }, 'standar_count')
            ->where('profile_name', 'ilike', '%' . $this->search . '%')
            ->where('role', 'auditee')
            ->where('is_active', true)
            ->orderBy('standar_count', 'desc')
            ->orderBy('profile_name', 'asc')
            ->paginate(10);

        return view('livewire.jadwal-unit-kerja', ['unit' => $unit])->layout('components.layouts.app')->title('Penjadwalan Unit Kerja');
    }
}
