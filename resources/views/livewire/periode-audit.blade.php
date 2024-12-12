@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Periode Audit</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('periode-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Periode Audit</h1>

    <div class="flex justify-between my-6 items-end flex-wrap gap-2">
        <div class="w-3/5 lg:w-[30%] flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-3">
            <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full sm:w-auto">
                <input type="text" readonly
                    class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full sm:w-auto flatpickr-free"
                    placeholder="Cari Tanggal Mulai" wire:model.live.debounce.400ms="search_start" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-4 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>
            <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full sm:w-auto">
                <input type="text" readonly
                    class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full sm:w-auto flatpickr-free"
                    placeholder="Cari Tanggal Akhir" wire:model.live.debounce.400ms="search_end" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-4 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>

            {{-- <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm w-full sm:w-auto"
                wire:click="resetSearch">Reset</button> --}}
        </div>

        <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm" wire:click="openModal('tambah')">
            Tambah
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md text-center">
                    <td>Status</td>
                    <td class="text-center">No</td>
                    <td>Tanggal Mulai</td>
                    <td>Tanggal Akhir</td>
                    <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($periode as $index => $item)
                    <tr class="text-center">
                        <td>
                            <div
                                class="badge {{ $item->is_active ? 'bg-[#60C0D0]' : 'bg-[#ff5861]' }} p-3 text-white border-none">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            {{ Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td>
                            {{ Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <th class="shadow-xl max-w-48">
                            <div class="flex justify-center items-center space-x-2">
                                <button wire:click="openModal('lihat', {{ $item->id }})">
                                    <i class="fas fa-eye text-black"></i>
                                </button>
                                <button wire:click="openModal('edit', {{ $item->id }})">
                                    <i class="fas fa-edit text-black"></i>
                                </button>
                                <button wire:click="openModal('hapus', {{ $item->id }})">
                                    <i class="fas fa-trash text-black"></i>
                                </button>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $periode->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Apakah anda yakin ingin menghapus periode <span class="text-red-500 font-medium">
                        {{ $tanggal_mulai }} -
                        {{ $tanggal_akhir }}</span>?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                            wire:click="resetModal">Tutup</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="delete">Ya,
                            hapus</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Tanggal Mulai <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : 'readonly' }} type="text"
                            placeholder="Masukkan tanggal mulai" wire:model="tanggal_mulai"
                            class="input input-bordered w-full input-md flatpickr-free @error('tanggal_mulai') border-red-500 @enderror" />

                        @error('tanggal_mulai')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Tanggal Akhir <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : 'readonly' }} type="text"
                            placeholder="Masukkan tanggal akhir" wire:model="tanggal_akhir"
                            class="input input-bordered w-full input-md flatpickr-free @error('tanggal_akhir') border-red-500 @enderror" />

                        @error('tanggal_akhir')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Status <span class="text-red-500">*</span></span>
                        </div>
                        <select wire:model="is_active" {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="select select-bordered select-md @error('is_active') border-red-500 @enderror">
                            <option value="true" selected>Aktif</option>
                            <option value="false">Nonaktif</option>
                        </select>

                        @error('is_active')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

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
