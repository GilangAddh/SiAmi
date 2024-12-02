<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class ManajemenPengguna extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $profileName = '';
    public $email = '';
    public $name = '';

    protected $rules = [
        'profileName' => 'required|max:255',
        'email' => 'required|email|max:255',
        'name' => 'required|max:255',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();
        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Pengguna';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    private function loadRecordData()
    {
        $user = User::findOrFail($this->recordId);
        $this->profileName = $user->profile_name;
        $this->email = $user->email;
        $this->name = $user->name;
    }

    public function saveData()
    {
        $this->validate();

        if ($this->modalAction === 'edit') {
            $user = User::findOrFail($this->recordId);
            $user->update([
                'profile_name' => $this->profileName,
                'email' => $this->email,
                'name' => $this->name,
            ]);
        } else {
            User::create([
                'profile_name' => $this->profileName,
                'email' => $this->email,
                'name' => $this->name,
            ]);
        }

        $this->resetModal();
    }

    public function deleteData()
    {
        if ($this->recordId) {
            User::findOrFail($this->recordId)->delete();
        }

        $this->resetModal();
    }

    public function resetModal()
    {
        $this->resetValidation();
        $this->reset(['isModalOpen', 'modalTitle', 'modalAction', 'recordId', 'profileName', 'email', 'name']);
    }

    public function render()
    {
        $users = User::where('profile_name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.manajemen-pengguna', compact('users'));
    }
}
