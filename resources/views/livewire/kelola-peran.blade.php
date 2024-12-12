<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Kelola Peran</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('kelola-peran') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Akses Peran</h1>

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
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Menu</td>
                    <td>Tipe</td>
                    <td>Url</td>
                    <td>Akses Peran</td>
                    <td class="text-center">Icon</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $index => $menu)
                    <tr>
                        <td class="text-center">{{ $menus->firstItem() + $index }}.</td>
                        <td>
                            {{ $menu->menu }}
                        </td>
                        <td class="capitalize">
                            {{ $menu->type == 'parent' || $menu->type == 'standalone' || $menu->type == 'hidden' ? $menu->type : 'Child ' . $menu->type }}
                        </td>
                        <td>{{ $menu->url }}</td>
                        <td>
                            @if (!empty($menu->roles))
                                {{ implode(', ', $menu->roles) }}
                            @else
                                Belum ada akses
                            @endif
                        </td>
                        <td class="text-center">
                            <i class="{{ $menu->icon }} w-5 h-5"></i>
                        </td>
                        <th class="shadow-xl">
                            <div class="flex justify-center items-center space-x-2">
                                <i class="fas fa-eye text-black cursor-pointer"
                                    wire:click="openModal('lihat', {{ $menu->id }})"></i>
                                <i class="fas fa-edit text-black cursor-pointer"
                                    wire:click="openModal('edit', {{ $menu->id }})"></i>
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
        {{ $menus->links() }}
    </div>


    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            <form wire:submit.prevent="saveData">
                <label class="form-control w-full mb-2">
                    <div class="label">
                        <span class="label-text">Nama Menu <span class="text-red-500">*</span></span>
                    </div>
                    <input disabled type="text" wire:model="menu" placeholder="Masukkan nama menu"
                        class="input input-bordered w-full input-md" />
                </label>

                <label class="form-control w-full mb-2">
                    <div class="label">
                        <span class="label-text">Tipe Menu <span class="text-red-500">*</span></span>
                    </div>
                    <input disabled type="text" wire:model="type" placeholder="Masukkan tipe menu"
                        class="input input-bordered w-full input-md" />
                </label>

                <label class="form-control
                        w-full mb-2">
                    <div class="label">
                        <span class="label-text">Url Menu</span>
                    </div>
                    <input disabled type="text" wire:model="url" placeholder="Masukkan url menu"
                        class="input input-bordered w-full input-md" />
                </label>

                <label class="form-control w-full mb-2">
                    <div class="label">
                        <span class="label-text">
                            Kode Icon <span class="text-red-500">*</span>
                        </span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <input disabled type="text" placeholder="Masukkan kode icon"
                            wire:model.live.debounce.300ms="icon" class="input input-bordered w-full input-md" />
                        <div class="bg-[#60c0d0] rounded-lg text-white py-4 px-5 flex items-center justify-center">
                            <i class="{{ $icon }}"></i>
                        </div>
                    </div>
                </label>

                <label class="form-control w-full mb-2">
                    <div class="label mb-1">
                        <span class="label-text">Akses Peran</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 px-1">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="checkbox" value="ppm"
                                wire:model="roles" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]" />
                            <span class="label-text">PPM</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="checkbox" value="auditor"
                                wire:model="roles" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]" />
                            <span class="label-text">Auditor</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="checkbox" value="auditee"
                                wire:model="roles" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]" />
                            <span class="label-text">Auditee</span>
                        </label>
                    </div>
                </label>

                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button type="button"
                            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                            wire:click="resetModal">Tutup</button>

                        @if ($modalAction != 'lihat')
                            <button type="submit" class="btn btn-sm bg-[#60c0d0] text-white">Simpan</button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </dialog>
</div>
