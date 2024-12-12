@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Unit Kerja</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('unit-kerja') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Unit Kerja</h1>

    <div class="flex justify-between my-6 items-center">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-3/5 md:w-1/4">
            <input wire:model.live.debounce.400ms="search" type="text"
                class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full" placeholder="Cari" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm" wire:click="openModal('tambah')">
            Tambah
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Profil Unit Kerja</td>
                    <td>Username</td>
                    <td class="text-center">Status</td>
                    <td class="text-center">Waktu Pembuatan Akun</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $index }}.</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="rounded-full h-12 w-12">
                                        <img
                                            src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('images/avatar.png') }}" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $user->profile_name }}</div>
                                    <div class="text-sm opacity-50">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td class="text-center">
                            <div
                                class="badge {{ $user->is_active ? 'bg-[#60C0D0]' : 'bg-[#ff5861]' }} p-3 text-white border-none">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td class="text-center">
                            {{ Carbon::parse($user->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                        </td>

                        <th class="shadow-xl">
                            <div class="flex justify-center items-center space-x-2">
                                <i class="fas fa-eye text-black cursor-pointer"
                                    wire:click="openModal('lihat', {{ $user->id }})"></i>
                                <i class="fas fa-edit text-black cursor-pointer"
                                    wire:click="openModal('edit', {{ $user->id }})"></i>
                                {{-- <i class="fas fa-trash text-black cursor-pointer"
                                    wire:click="openModal('hapus', {{ $user->id }})"></i> --}}
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>


    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Apakah anda yakin ingin menghapus <span class="text-red-500 font-medium">{{ $profileName }}</span>?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                            wire:click="resetModal">Tutup</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="deleteData">Ya,
                            hapus</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <div class="text-center">
                        <div class="avatar mt-2 justify-center flex mb-1">
                            <div class="w-28 rounded-full border border-1">
                                <img src='{{ $this->getProfilePhotoUrl() }}' />
                            </div>
                        </div>

                        @if ($modalAction != 'lihat')
                            <div class="flex justify-center">
                                <input type="file" wire:model.live="profile_photo_path" accept="image/*"
                                    class="file-input file-input-ghost file-input-sm w-full max-w-xs" />
                            </div>
                        @endif

                        @error('profile_photo_path')
                            <span class="text-red-500 text-sm error-message text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <label class="form-control w-full my-2">
                        <div class="label">
                            <span class="label-text">Nama Unit Kerja <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text" wire:model="profileName"
                            placeholder="Masukkan nama unit kerja"
                            class="input input-bordered w-full input-md @error('profileName') border-red-500 @enderror" />

                        @error('profileName')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Email <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="email" wire:model="email"
                            placeholder="Masukkan email aktif"
                            class="input input-bordered w-full input-md @error('email') border-red-500 @enderror" />

                        @error('email')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Username Akun <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text" wire:model="name"
                            placeholder="Masukkan username akun"
                            class="input input-bordered w-full input-md @error('name') border-red-500 @enderror" />

                        @error('name')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Status <span class="text-red-500">*</span></span>
                        </div>
                        <select wire:model="status" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="select select-bordered select-md @error('status') border-red-500 @enderror">
                            <option value="true" selected>Aktif</option>
                            <option value="false">Nonaktif</option>
                        </select>

                        @error('status')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    @if ($modalAction != 'lihat')

                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text">
                                    @if ($modalAction === 'edit')
                                        Reset
                                        @endif Password @if ($modalAction === 'tambah')
                                            <span class="text-red-500">*</span>
                                        @endif
                                </span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <input type="text" placeholder="Masukkan password"
                                    wire:model.live.debounce.300ms="password"
                                    class="input input-bordered w-full input-md @error('password') border-red-500 @enderror" />
                                <button type="button"
                                    class="btn text-white btn-sm px-4 bg-[#60c0d0] border-none px-3 text-sm"
                                    wire:click="generateRandomPassword">
                                    Acak
                                </button>
                            </div>

                            @error('password')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>


                        @if ($modalAction === 'tambah' || ($modalAction === 'edit' && $password))
                            <label class="form-control w-full mb-2">
                                <div class="label">
                                    <span class="label-text">
                                        Konfirmasi Password <span class="text-red-500">*</span>
                                    </span>
                                </div>
                                <input type="text" placeholder="Konfirmasi password" wire:model="confirmPassword"
                                    class="input input-bordered w-full input-md @error('confirmPassword') border-red-500 @enderror" />

                                @error('confirmPassword')
                                    <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                                @enderror
                            </label>
                        @endif
                    @endif

                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                                wire:click="resetModal">Tutup</button>

                            @if ($modalAction != 'lihat')
                                <button type="submit"
                                    class="btn btn-sm bg-[#60c0d0] text-white">{{ $modalAction === 'edit' ? 'Simpan' : 'Tambah' }}</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </dialog>
</div>
