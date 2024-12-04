@section('title', $title)

@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>{{ $title }}</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('standar-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl" wire:model.live.debounce.400ms="search">Data {{ $title }}</h1>

    <div class="flex justify-between my-6 items-center flex-wrap">
        <select class="select select-bordered w-3/5 md:w-1/4">
            <option value="" selected disabled>Cari Standar Audit</option>
            @foreach ($standar as $item)
                <option value="{{ $item->id }}">{{ $item->nama_standar }}</option>
            @endforeach
        </select>

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
                    <td>Standar Audit</td>
                    <td class="text-center">Pertanyaan</td>
                    <td>Indikator Pertanyaan</td>
                    <td class="text-center">Bukti Objektif</td>
                    <th class="bg-[#60c0d0] shadow-xl"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($indikator as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>
                            {{ $item->standarAudit->nama_standar }}
                        </td>
                        <td>
                            {{ $item->nomer_pertanyaan_standar }}. {{ $item->pertanyaan_standar }}
                        </td>
                        <td>
                            {{ $item->indikator_pertanyaan }}
                        </td>
                        <td class="text-center">
                            <a href="{{ asset('storage/' . $item->bukti_objektif) }}" target="_blank">
                                <i class="fa-solid fa-file
                                            text-black"></i>
                                <span class="text-[#60C0D0] underline ml-2">{{ $item->original_bukti_objektif }}</span>
                            </a>
                        </td>
                        <th class="shadow-xl">
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
                        <td colspan="6" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $indikator->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>

            @if ($modalAction === 'hapus')
                <p>Apakah anda yakin ingin menghapus <span
                        class="text-red-500 font-medium">{{ $pertanyaan_standar }}</span>?
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
                        <select {{ $modalAction === 'lihat' ? 'disabled' : '' }}
                            class="select select-bordered w-full @error('nomer_pertanyaan_standar') border-red-500 @enderror"
                            wire:model="id_standar">
                            <option value="" selected disabled>Standar Audit</option>
                            @foreach ($standar as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_standar }}</option>
                            @endforeach
                        </select>

                        @error('id_standar')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Nomor Pertanyaan Standar <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="number"
                            wire:model="nomer_pertanyaan_standar" placeholder="Masukkan nomor pertanyaan"
                            class="input input-bordered w-full input-md @error('nomer_pertanyaan_standar') border-red-500 @enderror" />

                        @error('nomer_pertanyaan_standar')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Pertanyaan Standar <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text"
                            wire:model="pertanyaan_standar" placeholder="Masukkan pertanyaan standar"
                            class="input input-bordered w-full input-md @error('pertanyaan_standar') border-red-500 @enderror" />

                        @error('pertanyaan_standar')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full mb-2">
                        <div class="label">
                            <span class="label-text">Indikator Pertanyaan <span class="text-red-500">*</span></span>
                        </div>
                        <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="text"
                            wire:model="indikator_pertanyaan" placeholder="Masukkan indikator pertanyaan"
                            class="input input-bordered w-full input-md @error('indikator_pertanyaan') border-red-500 @enderror" />

                        @error('indikator_pertanyaan')
                            <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                        @enderror
                    </label>

                    @if ($modalAction == 'tambah')
                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text">Bukti Objektif <span class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="file"
                                class="file-input file-input-ghost file-input-bordered w-full file-input-md @error('bukti_objektif') border-red-500 @enderror"
                                wire:model="bukti_objektif" />

                            @error('bukti_objektif')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif
                    @if ($modalAction == 'edit')
                        <label class="form-control w-full mb-2">
                            <div class="label">
                                <span class="label-text">Bukti Objektif <span class="text-red-500">*</span></span>
                            </div>
                            <input {{ $modalAction === 'lihat' ? 'disabled' : '' }} type="file"
                                class="file-input file-input-ghost file-input-bordered w-full file-input-md @error('new_bukti_objektif') border-red-500 @enderror"
                                wire:model="new_bukti_objektif" />

                            @error('new_bukti_objektif')
                                <span class="text-red-500 text-sm error-message">{{ $message }}</span>
                            @enderror
                        </label>
                    @endif
                    @if ($modalAction != 'tambah')
                        <label class="form-control w-full mb-2">
                            @if ($modalAction == 'lihat')
                                <div class="label">
                                    <span class="label-text">Bukti Objektif <span class="text-red-500">*</span></span>
                                </div>
                            @endif
                            <a href="{{ asset('storage/' . $bukti_objektif) }}" target="_blank"
                                class="underline text-[#60c0d0] ml-1">
                                Lihat File
                            </a>
                        </label>
                    @endif
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
