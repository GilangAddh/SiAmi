<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class DeskEvaluasi extends Component
{
    public function render()
    {
        return view('livewire.desk-evaluasi')
            ->layout('components.layouts.app')
            ->title('Desk Evaluasi')
            ->with([
                'role' => Auth::user()->role
            ]);
    }
}
