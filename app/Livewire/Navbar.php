<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Navbar extends Component
{
    public $menus;
    public $role;

    public function mount()
    {
        $this->role = Auth::check() ? Auth::user()->role : 'guest';

        $this->menus = Cache::remember('menus_' . $this->role, now()->addMinutes(30), function () {
            return Navigation::whereJsonContains('roles', $this->role)->get();
        });
    }

    public function render()
    {
        return view('livewire.navbar');
    }
}
