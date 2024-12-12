<?php

namespace App\Livewire;

use App\Models\Navigation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class Navbar extends Component
{
    public $menus;
    public $role;

    public function mount()
    {
        $this->role = Auth::user()->role;
        $this->menus = Navigation::whereJsonContains('roles', $this->role)->orderBy('id', 'asc')->where('type', '!=', 'hidden')->get();
    }

    public function render()
    {
        return view('livewire.navbar')->with([
            'profilePhoto' => Auth::user()->profile_photo_path,
            'profileName' => Auth::user()->profile_name,
            'role' => Auth::user()->role
        ]);
    }
}
