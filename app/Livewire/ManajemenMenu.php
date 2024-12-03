<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Navigation;

class ManajemenMenu extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $menu = '';
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
        $this->modalTitle = ucfirst($action) . ' Data Menu';

        $this->recordId = $recordId;
        $this->loadRecordData();

        $this->isModalOpen = true;
    }

    private function loadRecordData()
    {
        $menu = Navigation::findOrFail($this->recordId);
        $this->menu = $menu->menu;
        $this->url = $menu->url;
        $this->icon = $menu->icon;
        $this->roles = $menu->roles;
    }

    public function saveData()
    {
        $rules = [
            'menu' => 'required|max:255',
            'icon' => 'required|max:255',
            'roles' => 'nullable|array',
        ];

        $this->validate($rules);

        tap(Navigation::findOrFail($this->recordId), function ($menu) {
            $menu->update([
                'menu' => $this->menu,
                'icon' => $this->icon,
                'roles' => $this->roles ?? [],
            ]);
        });

        return redirect()->route('manajemen-menu');
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
            'url',
            'icon',
            'roles',
        ]);
    }

    public function render()
    {
        $menus = Navigation::where('menu', 'like', '%' . $this->search . '%')
            ->orWhere('url', 'like', '%' . $this->search . '%')
            ->orWhereJsonContains('roles', $this->search)
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        return view('livewire.manajemen-menu', compact('menus'));
    }
}
