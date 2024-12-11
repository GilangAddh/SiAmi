<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class PemetaanStandarAudit extends Component
{
    public $search = '';

    public function render()
    {
        $unit = User::query()
            ->where('role', operator: 'auditee')
            ->where(function ($query) {
                $query->where('profile_name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pemetaan-standar-audit', ['unit' => $unit]);
    }
}
