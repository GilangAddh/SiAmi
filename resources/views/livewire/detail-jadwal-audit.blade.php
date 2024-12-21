@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('jadwal-audit') }}">Jadwal Audit</a></li>
            <li>Jadwal Audit {{ $this->profile_name }}</li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Pemetaan Jadwal Audit {{ $this->profile_name }}</h1>

    <div class="flex justify-start my-6 items-center">
        <label class="form-control w-full md:w-1/4">
            <div class="label">
                <span class="label-text">Periode Audit</span>
            </div>
            <select class="select select-bordered w-full" wire:model.live="id_periode">
                <option disabled value="" selected>Pilih periode audit</option>
                @foreach ($periode as $item)
                    <option value="{{ $item->id }}" wire:key="{{ $item->id }}">
                        {{ Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} -
                        {{ Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg mt-6">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Nama Standar</td>
                    <td class="text-center">Status</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if ($id_periode)
                    @forelse ($standar as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}.</td>
                            <td>{{ $item->nama_standar }}</td>
                            <td class="text-center">
                                @if ($item->pernyataan_count > 0)
                                    <span class="font-semibold text-[#60c0d0]">Standar
                                        Digunakan</span>
                                @else
                                    <span class="font-semibold text-error">Standar
                                        Belum Digunakan</span>
                                @endif
                                <br>{{ $item->pernyataan_count }}
                                Pernyataan Standar Digunakan
                            </td>
                            <th class="shadow-xl text-center">
                                <div class="flex justify-center items-center space-x-2">
                                    <button class="btn btn-xs bg-[#60c0d0] text-white"
                                        wire:click="openModal('{{ $item->nama_standar }}', {{ $item->id }}, 'save')">
                                        @if ($item->pernyataan_count > 0)
                                            Edit Penerapan
                                        @else
                                            Terapkan
                                        @endif
                                    </button>

                                    @if ($item->pernyataan_count > 0)
                                        <button class="btn btn-xs bg-error text-white"
                                            wire:click="openModal('{{ $item->nama_standar }}', {{ $item->id }}, 'cancel')">Batal
                                            Terapkan</button>
                                    @endif
                                </div>
                            </th>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada standar yang ditemukan.</td>
                        </tr>
                    @endforelse
                @else
                    <tr>
                        <td colspan="4" class="text-center">Pilih periode audit terlebih dahulu.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- @if ($id_periode)
        <div class="mt-4">
            {{ $standar->links() }}
        </div>
    @endif --}}

    <div class="my-6 flex justify-between">
        <a class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            href="{{ route('jadwal-audit') }}">Kembali</a>
        {{-- @if ($id_periode)
            <button
                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
                wire:click="save">Simpan</button>
        @endif --}}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-4xl">
            @if ($modalAction === 'cancel')
                <p>Apakah anda yakin ingin membatalkan penerapan <span
                        class="text-red-500 font-medium">{{ $modalTitle }}</span>?
                </p>
                <div class="modal-action">
                    <div class="flex space-x-2 justify-end">
                        <button
                            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                            wire:click="resetModal">Tutup</button>

                        <button class="btn btn-error btn-sm text-white" wire:click="deleteData">Ya,
                            batalkan</button>
                    </div>
                </div>
            @else
                <form wire:submit.prevent="saveData">
                    <h3 class="text-lg font-bold mb-4">{{ $modalTitle }}</h3>
                    <button type="button" class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                        wire:click="resetModal">✕</button>
                    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
                        <table class="table table-zebra table-pin-cols">
                            <thead class="bg-[#60c0d0] text-white font-bold">
                                <tr class="text-md text-center">
                                    <td class="text-center">No</td>
                                    <td>Pernyataan</td>
                                    <td>Indikator</td>
                                    <td>Pertanyaan</td>
                                    <td>Bukti Objektif</td>
                                    <td>Gunakan Pernyataan</td>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pernyataan as $index => $item)
                                    <tr class="text-center">
                                        <td class="align-top">{{ $index + 1 }}.</td>
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
                                                <p class="">Belum ada indikator</p>
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
                                                <p class="text-center">Belum ada pertanyaan</p>
                                            @endif

                                        </td>
                                        <td class="max-w-48 align-top text-left">
                                            @if (!empty($item->bukti_objektif) && is_array($item->bukti_objektif))
                                                <ol class="list-none pl-5">
                                                    @foreach ($item->bukti_objektif as $index => $bukti)
                                                        <li class="mb-2"><a
                                                                class="link link-hover underline hover:text-[#60c0d0]"
                                                                href="{{ asset('storage/' . $bukti) }}"
                                                                target="_blank">
                                                                <i class="fa-solid fa-file text-black"></i>
                                                                <span class="ml-2">
                                                                    {{ $item->original_bukti_objektif[$index] ?? 'Bukti Objektif ' . ($index + 1) }}
                                                                </span>
                                                            </a></li>
                                                    @endforeach
                                                </ol>
                                            @else
                                                <p class="text-center">Belum ada bukti objektif</p>
                                            @endif
                                        </td>
                                        <th class="shadow-xl text-center">
                                            <input type="checkbox" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]"
                                                wire:model="selectedPernyataan" value="{{ $item->id }}">
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

                    <div class="modal-action">
                        <div class="flex space-x-2 justify-end">
                            <button type="button"
                                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                                wire:click="resetModal">Tutup</button>

                            @if (count($pernyataan) > 0)
                                <button type="submit" class="btn btn-sm bg-[#60c0d0] text-white">Simpan</button>
                            @endif
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </dialog>
</div>
