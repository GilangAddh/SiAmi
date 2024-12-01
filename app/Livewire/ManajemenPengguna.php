<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public $title;
    public $search = '';

    protected $queryString = ['search'];

    public function mount()
    {
        $this->title = "Manajemen Pengguna";
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.manajemen-pengguna', compact('users'));
    }
}
