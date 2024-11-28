<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;

class Home extends Component
{
    public $role;
    public $menu;

    public function mount()
    {
        $this->role = Auth::user()->role;
    }
    public function render()
    {
        if ($this->role == 'ppm') {
            $this->menu = Navigation::where('role', 'ppm')->get();
        } else if ($this->role == 'auditee') {
            $this->menu = Navigation::where('role', 'auditee')->get();
        } else if ($this->role == 'auditor') {
            $this->menu = Navigation::where('role', 'auditor')->get();
        }
        return view('livewire.home');
    }
}
