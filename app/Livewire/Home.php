<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
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
        return view('livewire.home', [
            'title' => $this->title,
        ]);
    }
}
