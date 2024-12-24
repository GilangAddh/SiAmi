<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\DeskEvaluasi as ModelsDeskEvaluasi;
use Livewire\WithPagination;

class DeskEvaluasi extends Component
{
    use WithPagination;

    public $search_start;
    public $search_end;

    public $search = '';
    public $audit_status;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $desk = ModelsDeskEvaluasi::select('desk_evaluasi.*')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id');
            }, 'total_pernyataan')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id')
                    ->where('kategori_temuan', 'Sesuai Standar');
            }, 'total_sesuai_standar')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id')
                    ->where('kategori_temuan', 'Observasi');
            }, 'total_observasi')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id')
                    ->where('kategori_temuan', 'KTS');
            }, 'total_kts')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id')
                    ->whereNotNull('detail_desk_evaluasi.latest_update_by_auditor')
                    ->where('detail_desk_evaluasi.latest_update_by_auditor', '!=', '');
            }, 'filled_auditor')
            ->selectSub(function ($query) {
                $query->from('detail_desk_evaluasi')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('detail_desk_evaluasi.id_desk', 'desk_evaluasi.id')
                    ->whereNotNull('detail_desk_evaluasi.latest_update_by_auditee')
                    ->where('detail_desk_evaluasi.latest_update_by_auditee', '!=', '');
            }, 'filled_auditee')
            ->when(Auth::user()->role == 'auditee', function ($query) {
                return $query->where('soft_unit', Auth::user()->id);
            })
            ->when(Auth::user()->role == 'auditor', function ($query) {
                return $query->whereJsonContains('soft_auditor', Auth::user()->id);
            })
            ->when($this->search_start, function ($query) {
                return $query->where('hard_periode_awal', '>=', $this->search_start);
            })
            ->when($this->search_end, function ($query) {
                return $query->where('hard_periode_akhir', '<=', $this->search_end);
            })
            ->when($this->audit_status, function ($query) {
                return $query->where('status', $this->audit_status);
            })
            ->where(function ($query) {
                $query->where('hard_unit', 'ilike', '%' . $this->search . '%')
                    ->orWhere('hard_standar', 'ilike', '%' . $this->search . '%');
            })
            ->orderBy('hard_periode_awal', 'desc')
            ->orderBy('hard_periode_akhir', 'desc')
            ->paginate(10);

        return view('livewire.desk-evaluasi', [
            'desk' => $desk,
            'role' => Auth::user()->role
        ])
            ->layout('components.layouts.app')
            ->title('Desk Evaluasi');
    }
}
