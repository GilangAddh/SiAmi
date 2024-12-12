<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('pemetaan-standar-audit') }}">Pemetaan Standar
                    Audit</a></li>
            <li>Pemetaan Standar</li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Unit Kerja</h1>

    <div class="my-6 items-center">
        <div class="w-full sm:w-1/2 lg:w-1/3 flex items-center gap-2 mb-8">
            <label class="form-control mb-2 label-text min-w-20">
                Nama Unit
            </label>
            <input disabled type="text" wire:model="profile_name" class="input input-bordered w-full input-md " />
        </div>
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Standar</td>
                    <td>Status</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Indikator</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($standar as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->nama_standar }}</td>
                        <td><input type="checkbox" class="checkbox mr-2" wire:model="selectedStandar"
                                value="{{ $item->id }}"
                                {{ in_array($item->id, $existingStandar) ? 'checked' : '' }}></td>
                        <th class="shadow-xl text-center">
                            <button wire:click="openModal('{{ $item->nama_standar }}',{{ $item->id }})">
                                <i class="fas fa-eye text-black"></i>
                            </button>
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
    <div class="my-4">
        <a class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            href="{{ route('pemetaan-standar-audit') }}">Kembali</a>
        <button
            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            wire:click="save">Simpan</button>

    </div>
    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="resetModal">✕</button>
            <hr>
            <table class="table table-zebra table-pin-cols">
                <thead class="bg-[#60c0d0] text-white font-bold">
                    <tr class="text-md text-center">
                        <td class="text-center">No</td>
                        <td>Nomor Pertanyaan</td>
                        <td>Pertanyaan</td>

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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </dialog>
</div>
