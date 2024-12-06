<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Auditor extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    protected $queryString = ['search'];

    public $isModalOpen = false;
    public $modalTitle = '';
    public $modalAction = '';
    public $recordId = null;

    public $name = '';
    public $email = '';
    public $status = true;
    public $profileName = '';
    public $password = '';
    public $confirmPassword = '';
    public $profile_photo_path = null;
    public $no_identity = '';

    public function generateRandomPassword()
    {
        $this->password = Str::random(8);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal($action, $recordId = null)
    {
        $this->resetModal();

        $this->modalAction = $action;
        $this->modalTitle = ucfirst($action) . ' Data Auditor';

        if (in_array($action, ['edit', 'lihat', 'hapus']) && $recordId) {
            $this->recordId = $recordId;
            $this->loadRecordData();
        }

        $this->isModalOpen = true;
    }

    private function loadRecordData()
    {
        $user = User::findOrFail($this->recordId);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->status = $user->is_active;
        $this->profileName = $user->profile_name;
        $this->profile_photo_path = $user->profile_photo_path;
        $this->no_identity = $user->no_identity;
    }

    public function saveData()
    {
        $rules = [
            'name' => 'required|max:255|min:5|regex:/^\S*$/',
            'no_identity' => 'required|max:255|min:5|regex:/^\d+$/',
            'email' => 'required|email|max:255|min:5|unique:users,email' . ($this->recordId ? ',' . $this->recordId : ''),
            'status' => 'required',
            'profileName' => 'required|max:255|min:3',
            'password' => $this->modalAction === 'tambah' ? 'required|min:8' : 'nullable|min:8',
            'confirmPassword' => $this->modalAction === 'tambah' || ($this->modalAction === 'edit' && $this->password) ? 'required|same:password' : 'nullable|same:password',
        ];

        $messages = [
            'name.regex' => 'The name must not contain any whitespace.',
            'no_identity.regex' => 'No identity must contain only numbers.',
        ];

        if ($this->profile_photo_path instanceof UploadedFile) {
            $rules['profile_photo_path'] = 'nullable|image|max:2048';
        }

        $this->validate($rules, $messages);

        $profilePhotoPath = $this->handleProfilePhotoUpload();

        if ($this->modalAction === 'edit') {
            tap(User::findOrFail($this->recordId), function ($user) use ($profilePhotoPath) {
                if ($profilePhotoPath) {
                    if ($user->profile_photo_path) {
                        Storage::delete('public/' . $user->profile_photo_path);
                    }

                    $user->profile_photo_path = $profilePhotoPath;
                }

                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'is_active' => $this->status,
                    'profile_name' => $this->profileName,
                    'password' => $this->password ? bcrypt($this->password) : $user->password,
                    'role' => 'auditor',
                    'no_identity' => $this->no_identity
                ]);
            });
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'is_active' => $this->status,
                'profile_name' => $this->profileName,
                'password' => bcrypt($this->password),
                'profile_photo_path' => $profilePhotoPath,
                'role' => 'auditor',
                'no_identity' => $this->no_identity
            ]);
        }

        $this->resetModal();
    }

    public function deleteData()
    {
        if ($this->recordId) {
            $user = User::findOrFail($this->recordId);

            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }

            $user->delete();
        }

        $this->resetModal();
    }

    public function resetModal()
    {
        $this->resetErrorBag();

        $this->reset([
            'isModalOpen',
            'modalTitle',
            'modalAction',
            'recordId',
            'name',
            'email',
            'status',
            'profileName',
            'password',
            'confirmPassword',
            'profile_photo_path',
            'no_identity'
        ]);
    }

    private function handleProfilePhotoUpload()
    {
        if ($this->profile_photo_path instanceof UploadedFile) {
            return $this->profile_photo_path->store('profile_photos', 'public');
        }

        return null;
    }

    public function getProfilePhotoUrl()
    {
        if (is_object($this->profile_photo_path) && method_exists($this->profile_photo_path, 'temporaryUrl')) {
            return $this->profile_photo_path->temporaryUrl();
        }

        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : asset('images/avatar.png');
    }


    public function render()
    {
        $users = User::query()
            ->where('role', 'auditor')
            ->where(function ($query) {
                $query->where('profile_name', 'like', '%' . $this->search . '%')
                    ->orWhere('no_identity', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.auditor', compact('users'));
    }
}
