<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Navigation;

class KelolaPeran extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $menu = '';
    public $type = '';
    public $url = '';
    public $icon = '';
    public $roles = [];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();

        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Data Akses Menu';

        $this->recordId = $recordId;
        $this->loadRecordData();

        $this->isModalOpen = true;
    }

    private function loadRecordData()
    {
        $menu = Navigation::findOrFail($this->recordId);
        $this->menu = $menu->menu;
        $this->type = $menu->type;
        $this->url = $menu->url;
        $this->icon = $menu->icon;
        $this->roles = $menu->roles;
    }

    public function saveData()
    {
        $rules = [
            'roles' => 'nullable|array',
        ];

        $this->validate($rules);

        tap(Navigation::findOrFail($this->recordId), function ($menu) {
            $menu->update([
                'roles' => $this->roles ?? [],
            ]);
        });

        return redirect()->route('kelola-peran');
    }

    public function resetModal()
    {
        $this->resetErrorBag();

        $this->reset([
            'isModalOpen',
            'modalTitle',
            'modalAction',
            'recordId',
            'menu',
            'type',
            'url',
            'icon',
            'roles'
        ]);
    }

    public function render()
    {
        $menus = Navigation::where('menu', 'ilike', '%' . $this->search . '%')
            ->orWhere('url', 'ilike', '%' . $this->search . '%')
            ->orWhere('type', 'ilike', '%' . $this->search . '%')
            ->orWhereJsonContains('roles', $this->search)
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('livewire.kelola-peran', compact('menus'))->layout('components.layouts.app')->title('Kelola Peran');
    }
}
