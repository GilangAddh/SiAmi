<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DeskEvaluasi;
use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\DetailDeskEvaluasi as ModelsDetailDeskEvaluasi;

class DetailDeskEvaluasi extends Component
{
    use WithFileUploads;

    public $is_auditor = false;
    public $is_auditee = false;
    public $is_authorized = true;
    public $action;
    public $desk;

    public function mount(DeskEvaluasi $desk, $action)
    {
        $userRole = Auth::user()->role;

        $this->is_auditor = $userRole == 'auditor';
        $this->is_auditee = $userRole == 'auditee';

        if ($this->is_auditee) {
            $this->is_authorized = $desk->soft_unit == Auth::user()->id;
        } elseif ($this->is_auditor) {
            $this->is_authorized = in_array(Auth::user()->id, $desk->soft_auditor ?? []);
        }

        if (!$this->is_authorized || !in_array($action, ['edit', 'view'])) {
            return redirect('/desk-evaluasi');
        }

        $this->action = $action;
        $this->desk = $desk;
    }

    public function render()
    {
        $detail = ModelsDetailDeskEvaluasi::where('id_desk', $this->desk->id)
            ->orderBy('id_pernyataan', 'asc')
            ->get();

        return view('livewire.detail-desk-evaluasi', compact('detail'))->layout('components.layouts.app')->title("Lembar Desk Evaluasi");
    }
}
