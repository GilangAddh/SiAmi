<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public $title;

    public function mount()
    {
        $this->title = "Dashboard ";
    }

    public function render()
    {
        return view('livewire.home');
    }
}
