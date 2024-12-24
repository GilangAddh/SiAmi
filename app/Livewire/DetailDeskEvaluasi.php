<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DeskEvaluasi;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailDeskEvaluasi as ModelsDetailDeskEvaluasi;

class DetailDeskEvaluasi extends Component
{
    public $is_authorized = true;

    public function mount(DeskEvaluasi $desk)
    {
        if (Auth::user()->role == 'auditee') {
            $is_authorized = DeskEvaluasi::where('soft_unit', Auth::user()->id)
                ->where('id', $desk->id)
                ->exists();
        } elseif (Auth::user()->role == 'auditor') {
            $is_authorized = DeskEvaluasi::whereJsonContains('soft_auditor', Auth::user()->id)
                ->where('id', $desk->id)
                ->exists();
        }

        if (!$is_authorized) {
            $this->redirect('/desk-evaluasi', navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.detail-desk-evaluasi');
    }
}
