<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('standar-audit') }}">Standar Audit</a></li>
            <li>{{ $subtitle }}</li>
            <li>Indikator {{ $subtitle }}</li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl" wire:model.live.debounce.400ms="search">Data Pernyataan {{ $subtitle }}</h1>

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

        <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm" wire:click="openModal('tambah')">
            Tambah
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md text-center">
                    <td class="text-center">Status</td>
                    <td class="text-center">No</td>
                    <td>Standar Audit</td>
                    <td>Pernyataan</td>
                    <td>Indikator</td>
                    <td>Pertanyaan</td>
                    <td>Bukti Objektif</td>
                    <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($indikator as $index => $item)
                    <tr class="text-center align-top">
                        <td class="align-top">
                            <div
                                class="badge {{ $item->is_active ? 'bg-[#60C0D0]' : 'bg-[#ff5861]' }} p-3 text-white border-none">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</div>
                        </td>
                        <td class="align-top">{{ $index + 1 }}.</td>
                        <td class="align-top">
                            {{ $item->standarAudit->nama_standar }}
                        </td>
                        <td class="max-w-64 text-justify align-top">
                            {{ $item->pernyataan_standar }}
                        </td>
                        <td class="align-top max-w-64 text-justify">
                            @if (!empty($item->indikator_pertanyaan) && count($item->indikator_pertanyaan) > 0)
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->indikator_pertanyaan as $indikatorItem)
                                        <li class="mb-2">{{ $indikatorItem }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p>Belum ada indikator</p>
                            @endif
                        </td>
                        <td class="max-w-64 text-justify align-top">
                            @if (!empty($item->pertanyaan) && count($item->pertanyaan) > 0)
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->pertanyaan as $pertanyaanItem)
                                        <li class="mb-2">{{ $pertanyaanItem }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p>Belum ada pertanyaan</p>
                            @endif

                        </td>
                        <td class="max-w-40 align-top">
                            @if (!empty($item->bukti_objektif))
                                <a class="link link-hover" href="{{ asset('storage/' . $item->bukti_objektif) }}"
                                    target="_blank">
                                    <i
                                        class="fa-solid fa-file
                                            text-black"></i>
                                    <span class="ml-2">{{ $item->original_bukti_objektif }}</span>
                                </a>
                            @else
                                <p>Belum ada bukti objektif</p>
                            @endif
                        </td>
                        <th class="shadow-xl align-top">
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
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $indikator->links() }}
    </div>
    <div class="my-4">
        <a class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            href="{{ route('standar-audit') }}">Kembali</a>
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Apakah anda yakin ingin menghapus pernyataan ini?
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
                            <span class="label-text">Standar Audit <span class="text-red-500">*</span></span>
                        </div>

                        <input disabled type="text" value="{{ $subtitle }}"
                            class="input input-bordered w-full input-md" />
                    </label>

                    <div class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Pertanyaan Standar <span class="text-red-500">*</span></span>
                        </div>
                        <textarea {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text" wire:model="pernyataan_standar"
                            placeholder="Masukkan pernyataan standar"
                            class="textarea textarea-bordered w-full @error('pernyataan_standar') border-red-500 @enderror" rows="2"></textarea>
                        @error('pernyataan_standar')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-control w-full mb-2">
                        <div class="label">
                            <label class="label-text">Indikator</label>
                            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm"><i
                                    class="fa-solid fa-plus"></i></button>
                        </div>
                        <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
                            <table class="table table-zebra table-pin-cols">
                                <thead class="bg-[#60c0d0] text-white font-bold">
                                    <tr class="text-md text-center">
                                        <td class="text-center">No</td>
                                        <td>Indikator</td>
                                        @if ($modalAction !== 'lihat')
                                            <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($indikator_pertanyaan as $index => $indikator)
                                        <tr class="text-center align-top">
                                            <td class="w-[10%] p-2">{{ $index + 1 }}</td>
                                            <td class="w-[80%] p-2">
                                                <textarea {{ $modalAction === 'lihat' ? 'disabled' : '' }} class="textarea textarea-bordered w-full"
                                                    wire:model="indikator_pertanyaan.{{ $index }}"></textarea>
                                            </td>
                                            @if ($modalAction !== 'lihat')
                                                <td class="w-[10%] p-2">
                                                    <button class="btn btn-sm bg-[#ff5861]"
                                                        wire:click="hapusIndikator({{ $index }})">
                                                        <i class="fas fa-trash text-white"></i>
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="form-control w-full mb-2">
                        <div class="label">
                            <label class="label-text">Pertanyaan</label>
                            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm"><i
                                    class="fa-solid fa-plus"></i></button>
                        </div>
                        <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
                            <table class="table table-zebra table-pin-cols">
                                <thead class="bg-[#60c0d0] text-white font-bold">
                                    <tr class="text-md text-center">
                                        <td class="text-center">No</td>
                                        <td>Pertanyaan</td>
                                        @if ($modalAction !== 'lihat')
                                            <th class="bg-[#60c0d0] shadow-xl">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pertanyaan as $index => $pertanyaanItem)
                                        <tr class="text-center align-top">
                                            <td class="w-[10%] p-2">{{ $index + 1 }}</td>
                                            <td class="w-[80%] p-2">
                                                <textarea {{ $modalAction === 'lihat' ? 'disabled' : '' }} class="textarea textarea-bordered w-full"
                                                    wire:model="pertanyaan.{{ $index }}"></textarea>
                                            </td>
                                            @if ($modalAction !== 'lihat')
                                                <td class="w-[10%] p-2">
                                                    <button class="btn btn-sm bg-[#ff5861]"><i
                                                            class="fas fa-trash text-white"></i></button>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <div class="form-control w-full mb-2">
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
                    </div>
                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button
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
