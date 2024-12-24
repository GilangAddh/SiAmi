<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UnitKerja extends Component
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

    public $file;
    public $rows = [];

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        $this->processFile();
    }

    public function processFile()
    {
        $path = $this->file->store('temp');
        $data = Excel::toArray([], Storage::path($path));

        if (!empty($data)) {
            unset($data[0][0]);

            $this->rows = array_map(function ($row) {
                return array_slice($row, 0, 4);
            }, $data[0]);
        }

        Storage::delete($path);
    }

    public function saveFromExcel()
    {
        $dataToInsert = [];

        foreach ($this->rows as $index => $row) {
            $messages = [
                // Profile name
                "rows.$index.0.required" => "The profile name for row is required.",
                "rows.$index.0.max" => "The profile name for row cannot be more than 255 characters.",
                "rows.$index.0.min" => "The profile name for row must be at least 3 characters.",

                // Email
                "rows.$index.1.required" => "The email for row is required.",
                "rows.$index.1.email" => "The email for row must be a valid email address.",
                "rows.$index.1.max" => "The email for row cannot be more than 255 characters.",
                "rows.$index.1.min" => "The email for row must be at least 5 characters.",
                "rows.$index.1.unique" => "The email for row is already taken.",

                // Username
                "rows.$index.2.required" => "The username for row is required.",
                "rows.$index.2.max" => "The username for row cannot be more than 255 characters.",
                "rows.$index.2.min" => "The username for row must be at least 5 characters.",
                "rows.$index.2.regex" => "The username for row cannot contain spaces.",

                // Password
                "rows.$index.3.required" => "The password for row is required.",
                "rows.$index.3.min" => "The password for row must be at least 8 characters.",
            ];

            $this->validate([
                "rows.$index.0" => 'required|max:255|min:3',  // profile_name
                "rows.$index.1" => 'required|email|max:255|min:5|unique:users,email',  // email
                "rows.$index.2" => 'required|max:255|min:5|regex:/^\S*$/',  // username
                "rows.$index.3" => 'required|string|min:8',    // password
            ], $messages);

            $dataToInsert[] = [
                'profile_name' => $row[0],
                'email' => strtolower($row[1]),
                'name' => strtolower($row[2]),
                'password' => bcrypt($row[3]),
                'is_active' => true,
                'role' => 'auditee',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        User::insert($dataToInsert);

        $this->resetSearch();
        $this->resetModal();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data unit kerja berhasil disimpan."})');
    }

    public function deleteRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }


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
        $this->modalTitle = ucfirst($action) . ' Data Unit Kerja';

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
    }

    public function saveData()
    {
        $rules = [
            'name' => 'required|max:255|min:5|regex:/^\S*$/',
            'email' => 'required|email|max:255|min:5|unique:users,email' . ($this->recordId ? ',' . $this->recordId : ''),
            'status' => 'required',
            'profileName' => 'required|max:255|min:3',
            'password' => $this->modalAction === 'tambah' ? 'required|min:8' : 'nullable|min:8',
            'confirmPassword' => $this->modalAction === 'tambah' || ($this->modalAction === 'edit' && $this->password) ? 'required|same:password' : 'nullable|same:password',
        ];

        $messages = [
            'name.regex' => 'The name must not contain any whitespace.',
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
                    'name' => strtolower($this->name),
                    'email' => strtolower($this->email),
                    'is_active' => $this->status,
                    'profile_name' => $this->profileName,
                    'password' => $this->password ? bcrypt($this->password) : $user->password,
                    'role' => 'auditee',
                ]);
            });
        } else {
            User::create([
                'name' => strtolower($this->name),
                'email' => strtolower($this->email),
                'is_active' => $this->status,
                'profile_name' => $this->profileName,
                'password' => bcrypt($this->password),
                'profile_photo_path' => $profilePhotoPath,
                'role' => 'auditee',
            ]);
        }

        $this->resetSearch();
        $this->resetModal();

        $this->js('SwalGlobal.fire({icon: "success", title: "Berhasil", text: "Data unit kerja berhasil disimpan."})');
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

        $this->resetSearch();
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
            'file',
            'rows'
        ]);
    }

    public function resetSearch()
    {
        $this->reset(['search']);
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
            ->where('role', 'auditee')
            ->where(function ($query) {
                $query->where('profile_name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('email', 'ilike', '%' . $this->search . '%')
                    ->orWhere('name', 'ilike', '%' . $this->search . '%');
            })
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.unit-kerja', compact('users'))->layout('components.layouts.app')->title("Unit Kerja");
    }
}
