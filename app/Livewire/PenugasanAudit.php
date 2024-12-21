<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class PenugasanAudit extends Component
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
            ->orderBy('profile_name', 'asc')
            ->paginate(10);

        return view('livewire.penugasan-audit', ['unit' => $unit])->layout('components.layouts.app')->title('Penugasan Audit');
    }
}
