<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class PemetaanStandarAudit extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $unit = User::query()
            ->where('role', operator: 'auditee')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('profile_name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('email', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pemetaan-standar-audit', ['unit' => $unit])->layout('components.layouts.app')->title('Pemetaan Standar Audit');
    }
}
