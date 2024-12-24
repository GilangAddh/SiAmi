@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Penugasan Audit</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="/penugasan-audit">Index</a>
            </li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Jadwal Audit</h1>

    <div class="flex justify-between my-6 items-center flex-wrap gap-4">
        <select class="select select-bordered w-full md:w-1/4" wire:model.live="id_periode">
            <option value="" selected>Seluruh Periode</option>
            @foreach ($periode as $item)
                <option value="{{ $item->id }}" wire:key="{{ $item->id }}">
                    {{ Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} -
                    {{ Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}</option>
            @endforeach
        </select>

        <select class="select select-bordered w-full md:w-1/4" wire:model.live="sortStatus">
            <option value="sudah" selected>Urutkan Status Sudah - Belum Ditugaskan</option>
            <option value="belum">Urutkan Status Belum - Sudah Ditugaskan</option>
        </select>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Periode Audit</td>
                    <td>Unit Kerja</td>
                    <td>Standar Audit</td>
                    <td class="text-center">Status Penugasan</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Penugasan Audit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwalAudit as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ Carbon::parse($item->periodeAudit->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }}
                            -
                            {{ Carbon::parse($item->periodeAudit->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td>{{ $item->unitKerja->profile_name }}</td>
                        <td>
                            <ol class="list-decimal pl-5">
                                @foreach (explode('| ', $item->ordered_standards) as $standard)
                                    <li>{{ $standard }}</li>
                                @endforeach
                            </ol>
                        </td>
                        <td class="text-center">
                            @if ($item->auditor_count > 0)
                                <span class="font-semibold text-[#60c0d0]">Sudah Ditugaskan</span>
                            @else
                                <span class="font-semibold text-error">Belum Ditugaskan</span>
                            @endif
                            <br>{{ $item->auditor_count }}
                            Auditor Ditugaskan untuk Audit
                        </td>
                        <th class="shadow-xl text-center">
                            <div class="flex justify-evenly mx-3 gap-2 md:gap-0 md:mx-0 ">
                                <a wire:navigate
                                    href="{{ route('detail-penugasan-audit', ['periode' => $item->periodeAudit, 'unitKerja' => $item->unitKerja]) }}"
                                    class="text-[#60c0d0]"><i class="fa-solid fa-stamp w-4 h-4 mr-1"></i>
                                    <span class="hidden md:inline">Auditor</span></a>

                                @if ($item->auditor_count > 0 && !$item->is_generated)
                                    <button class="btn btn-xs bg-[#60c0d0] text-white"
                                        wire:click="openModal(
                                        '{{ $item->unitKerja->profile_name }}',
                                        '{{ Carbon::parse($item->periodeAudit->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} - {{ Carbon::parse($item->periodeAudit->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}',
                                        {{ $item->unitKerja->id }},
                                        {{ $item->periodeAudit->id }}
                                    )">
                                        Generasi Desk
                                    </button>
                                @elseif ($item->auditor_count > 0 && $item->is_generated)
                                    <button class="btn btn-xs bg-[#60c0d0] text-white" wire:navigate
                                        href="/desk-evaluasi">
                                        Lihat Desk
                                    </button>
                                @endif
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
        {{ $jadwalAudit->links() }}
    </div>

    <dialog class="modal" @if ($isModalOpen) open @endif>
        <div class="modal-box w-full max-w-2xl">
            <h3 class="text-lg font-bold mb-4">Konfirmasi Generasi Desk evaluasi</h3>
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" wire:click="closeModal">✕</button>

            <p>Apakah anda yakin ingin generasi desk evaluasi untuk <span
                    class="text-[#60c0d0]">{{ $unit }}</span>
                periode <span class="text-[#60c0d0]">{{ $concat_periode }}</span>? Data
                setelah generasi <span class="text-error">tidak bisa diubah!</span>
            </p>

            <div class="modal-action">
                <div class="flex space-x-2 justify-end">
                    <button
                        class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white hover:border-none"
                        wire:click="closeModal">Batal</button>

                    <button class="btn bg-[#60c0d0] btn-sm text-white" wire:click="generate">Ya,
                        konfirmasi generasi</button>
                </div>
            </div>
        </div>
    </dialog>
</div>
