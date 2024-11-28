<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public $menus = [];
    public $role = '';

    public function mount()
    {
        $this->role = Auth::user()->role;

        if ($this->role == 'ppm') {
            $this->menus = Navigation::where('role', 'ppm')->get();
        } else if ($this->role == 'auditee') {
            $this->menus = Navigation::where('role', 'auditee')->get();
        } else if ($this->role == 'auditor') {
            $this->menus = Navigation::where('role', 'auditor')->get();
        }
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
