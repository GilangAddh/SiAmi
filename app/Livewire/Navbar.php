<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public $menus;
    public $role;

    public function mount()
    {
        $this->role = Auth::user()->role;
        $this->menus = Navigation::whereJsonContains('roles', $this->role)->orderBy('created_at', 'asc')->where('type', '!=', 'hidden')->get();
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
