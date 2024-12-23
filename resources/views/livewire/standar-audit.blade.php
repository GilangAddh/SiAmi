@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Standar Audit</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="/standar-audit">Index</a>
            </li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Standar Audit</h1>

    <div class="flex justify-between my-6 items-center flex-wrap">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-3/5 md:w-1/4">
            <input type="text" class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full"
                placeholder="Cari" wire:model.live.debounce.400ms="search" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <div class="flex justify-end space-x-4">
            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm"
                wire:click="openModal('import')">
                Import Excel
                <i class="fa-solid fa-file"></i>
            </button>
            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm"
                wire:click="openModal('tambah')">
                Tambah
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md text-center">
                    <td>Status</td>
                    <td class="text-center">No</td>
                    <td>Nama Standar</td>
                    <td>Nomor Dokumen</td>
                    <td>Nomor Revisi</td>
                    <td>Tanggal Terbit</td>
                    <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($standar as $index => $item)
                    <tr class="text-center">
                        <td>
                            <div
                                class="badge {{ $item->is_active ? 'bg-[#60C0D0]' : 'bg-[#ff5861]' }} p-3 text-white border-none">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td>{{ $index + 1 }}.</td>
                        <td>
                            {{ $item->nama_standar }}
                        </td>
                        <td>
                            {{ $item->nomer_dokumen }}
                        </td>
                        <td>
                            {{ $item->nomer_revisi }}
                        </td>
                        <td>
                            {{ Carbon::parse($item->tanggal_terbit)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <th class="shadow-xl max-w-48">
                            <div class="flex gap-2 md:justify-evenly">
                                <div>
                                    <a wire:navigate href="{{ route('pernyataan-standar', ['standarAudit' => $item]) }}"
                                        class="text-[#60c0d0]"><i class="fa-solid fa-pen"></i> <span
                                            class="hidden md:inline">Pernyataan</span></a>
                                </div>
                                <div class="flex justify-center items-center space-x-2">
                                    <button wire:click="openModal('lihat', {{ $item->id }})">
                                        <i class="fas fa-eye text-black"></i>
                                    </button>
                                    <button wire:click="openModal('edit', {{ $item->id }})">
                                        <i class="fas fa-edit text-black"></i>
                                    </button>
                                    {{-- <button wire:click="openModal('hapus', {{ $item->id }})">
                                        <i class="fas fa-trash text-black"></i>
                                    </button> --}}
                                </div>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $standar->links() }}
    </div>
    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-3xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'import')
                <p class="mb-3">Harap unggah file excel menggunakan format berikut: <a class="link link-success"
                        href="{{ asset('excels/format_unit_kerja.xlsx') }}">Unduh
                        Format</a>
                </p>

                <input wire:model="file" type="file"
                    class="file-input file-input-ghost file-input-bordered w-full file-input-md  @error('file') border-red-500 @enderror" />

                @error('file')
                    <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                @enderror

                <div wire:loading wire:target="file" class="text-sm text-gray-500 mt-2">
                    Memuat file...
                </div>

                @if ($rows)
                    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg mt-6">
                        <table class="table table-zebra">
                            <thead class="bg-[#60c0d0] text-white font-bold">
                                <tr class="text-md">
                                    <td class="text-center">Nama Standar</td>
                                    <td class="text-center">Nomor Dokumen</td>
                                    <td class="text-center">Nomor Revisi</td>
                                    <td class="text-center">Tanggal Terbit</td>
                                    <td class="text-center">Aksi</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $index => $row)
                                    <tr>
                                        @foreach ($row as $cellIndex => $cell)
                                            <td>
                                                @if ($cellIndex === count($row) - 1)
                                                    <input type="date"
                                                        wire:model="rows.{{ $index }}.{{ $cellIndex }}"
                                                        class="input input-bordered w-36 input-md @error('rows.' . $index . '.' . $cellIndex) border-red-500 @enderror"
                                                        value="{{ $cell }}" />
                                                @else
                                                    <input type="text"
                                                        wire:model="rows.{{ $index }}.{{ $cellIndex }}"
                                                        class="input input-bordered w-36 input-md @error('rows.' . $index . '.' . $cellIndex) border-red-500 @enderror"
                                                        value="{{ $cell }}" />
                                                @endif

                                                @error('rows.' . $index . '.' . $cellIndex)
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        @endforeach
                                        <td>
                                            <button class="btn btn-sm bg-[#ff5861]"
                                                wire:click="deleteRow({{ $index }})" type="button">
                                                <i class="fas fa-trash text-white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                                wire:click="resetModal">Batal</button>

                            <button type="submit" class="btn btn-sm bg-[#60c0d0] text-white"
                                wire:click="saveFromExcel">Konfirmasi Simpan</button>
                        </div>
                    </div>
                @endif
            @else
                @if ($modalAction === 'hapus')
                    <p>Apakah anda yakin ingin menghapus <span
                            class="text-red-500 font-medium">{{ $nama_standar }}</span>?
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
                                <span class="label-text md:text-[16px]">Nama Standar <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text"
                                wire:model="nama_standar" placeholder="Masukkan nama standar"
                                class="input input-bordered w-full input-md @error('nama_standar') border-red-500 @enderror" />

                            @error('nama_standar')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Nomor Dokumen <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text"
                                wire:model="nomer_dokumen" placeholder="Masukkan nomor dokumen"
                                class="input input-bordered w-full input-md @error('nomer_dokumen') border-red-500 @enderror" />

                            @error('nomer_dokumen')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Nomor Revisi <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text"
                                wire:model="nomer_revisi" placeholder="Masukkan nomor revisi"
                                class="input input-bordered w-full input-md @error('nomer_revisi') border-red-500 @enderror" />

                            @error('nomer_revisi')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Tanggal Terbit <span
                                        class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : 'readonly' }} type="text"
                                placeholder="Masukkan tanggal terbit" wire:model="tanggal_terbit"
                                class="input input-bordered w-full input-md flatpickr-free @error('tanggal_terbit') border-red-500 @enderror" />

                            @error('tanggal_terbit')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text md:text-[16px]">Status <span
                                        class="text-red-500">*</span></span>
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
            @endif

        </div>
    </dialog>
</div>
