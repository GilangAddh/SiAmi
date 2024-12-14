<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('pemetaan-standar-audit') }}">Pemetaan Standar
                    Audit</a></li>
            <li>Pemetaan Standar {{ $this->profile_name }}</li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Standar Audit {{ $this->profile_name }}</h1>

    {{-- <div class="flex justify-between my-6 items-center">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-3/5 md:w-1/4">
            <input wire:model.live.debounce.400ms="search" type="text"
                class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full" placeholder="Cari" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>
    </div> --}}

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg mt-6">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Nama Standar</td>
                    <td class="text-center">Lihat Indikator</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Terapkan Standar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($standar as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $item->nama_standar }}</td>
                        <td class="text-center">
                            <button wire:click="openModal('{{ $item->nama_standar }}', {{ $item->id }})">
                                <i class="fas fa-eye text-black"></i>
                            </button>
                        </td>
                        <th class="shadow-xl text-center">
                            <input type="checkbox" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]"
                                wire:model="selectedStandar" value="{{ $item->id }}">
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="my-6 flex justify-between">
        <a class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            href="{{ route('pemetaan-standar-audit') }}">Kembali</a>
        <button
            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            wire:click="save">Simpan</button>
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-4xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>
            <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
                <table class="table table-zebra table-pin-cols">
                    <thead class="bg-[#60c0d0] text-white font-bold">
                        <tr class="text-md text-center">
                            <td class="text-center">No</td>
                            <td>Nomor Pertanyaan</td>
                            <td>Pertanyaan</td>
                            <td>Indikator Pertanyaan</td>
                            <td>Bukti Objektif</td>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($indikator as $index => $item)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}.</td>
                                <td>
                                    {{ $item->nomer_pertanyaan_standar }}
                                </td>
                                <td class="max-w-64 text-justify">
                                    {{ $item->pertanyaan_standar }}
                                </td>
                                <td>
                                    {{ $item->indikator_pertanyaan }}
                                </td>
                                <td class="max-w-40">
                                    <a class="link link-hover" href="{{ asset('storage/' . $item->bukti_objektif) }}"
                                        target="_blank">
                                        <i
                                            class="fa-solid fa-file
                                                    text-black"></i>
                                        <span class="ml-2">{{ $item->original_bukti_objektif }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </dialog>
</div>
