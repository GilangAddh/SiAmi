<?php

namespace App\Livewire;

use Livewire\Component;

class ManajemenPengguna extends Component
{
    public $title;

    public function mount()
    {
        $this->title = "Manajemen Pengguna";
    }

    public function render()
    {
        return view('livewire.manajemen-pengguna');
    }
}
