@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Desk Evaluasi</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="/desk-evaluasi">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Desk Evaluasi</h1>

    <div class="flex justify-between my-6 items-end flex-wrap gap-4">
        <div class="w-3/5 lg:w-[60%] flex flex-col sm:flex-row sm:items-center sm:gap-4 gap-3">
            <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full sm:w-auto">
                <input type="text" readonly wire:model.live.debounce.400ms="search_start"
                    class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full sm:w-auto flatpickr-free"
                    placeholder="Tanggal Mulai" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-4 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>
            <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full sm:w-auto">
                <input type="text" readonly wire:model.live.debounce.400ms="search_end"
                    class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full sm:w-auto flatpickr-free"
                    placeholder="Tanggal Akhir" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-4 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>
            <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full sm:w-auto">
                <input type="text" wire:model.live.debounce.400ms="search"
                    class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2  w-full sm:w-auto"
                    placeholder="Cari" />
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                    class="h-4 w-5 opacity-70">
                    <path fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                </svg>
            </label>
        </div>

        <select class="select select-bordered w-full md:w-1/6" wire:model.live="audit_status">
            <option value="" selected>Semua Status Audit</option>
            <option value="Pengisian Evaluasi">Pengisian Evaluasi</option>
            <option value="Audit Lapangan">Audit Lapangan</option>
            <option value="Perbaikan KTS">Perbaikan KTS</option>
            <option value="Selesai">Selesai</option>
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
                    <td>Auditor</td>
                    <td>Frekuensi Temuan</td>
                    <td>Persentase Audit</td>
                    <td class="text-center">Status</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($desk as $index => $item)
                    <tr>
                        <td class="text-center">
                            {{ $index + 1 }}.
                        </td>
                        <td>
                            {{ Carbon::parse($item->hard_periode_awal)->locale('id')->translatedFormat('d F Y') }} -
                            {{ Carbon::parse($item->hard_periode_akhir)->locale('id')->translatedFormat('d F Y') }}
                        </td>
                        <td>
                            {{ $item->hard_unit }}
                        </td>
                        <td>
                            {{ $item->hard_standar }}
                        </td>
                        <td>
                            <ol class="list-decimal">
                                @foreach ($item->hard_auditor as $auditor)
                                    <li>{{ $auditor }}</li>
                                @endforeach
                            </ol>
                        </td>
                        <td>
                            <ul class="list-none">
                                <li class="mb-2">Sesuai Standar:<br><span
                                        class="text-[#60c0d0] font-medium">{{ $item->total_sesuai_standar }}/{{ $item->total_pernyataan }}
                                        Pernyataan</span></li>
                                <li class="mb-2">Observasi:<br><span
                                        class="text-[#60c0d0] font-medium">{{ $item->total_observasi }}/{{ $item->total_pernyataan }}
                                        Pernyataan</span></li>
                                <li>KTS:<br><span
                                        class="text-[#60c0d0] font-medium">{{ $item->total_kts }}/{{ $item->total_pernyataan }}
                                        Pernyataan</span></li>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-none">
                                <li class="mb-2">Pengisian Evaluasi:<br><span
                                        class="text-[#60c0d0]">{{ $item->filled_auditee }}/{{ $item->total_pernyataan }}
                                        Pernyataan
                                        Dilengkapi</span>
                                </li>
                                <li>Audit Lapangan:<br><span
                                        class="text-[#60c0d0]">{{ $item->filled_auditor }}/{{ $item->total_pernyataan }}
                                        Pernyataan Dilengkapi</span>
                                </li>
                            </ul>
                        </td>
                        <td class="text-center">
                            @if (Carbon::today()->lt(Carbon::parse($item->hard_periode_awal)))
                                <span class="text-error">Menunggu Periode</span><br>
                            @elseif (Carbon::today()->gt(Carbon::parse($item->hard_periode_akhir)))
                                <span class="text-error">Periode Berakhir</span><br>
                            @endif
                            {{ $item->status }}
                        </td>
                        <th class="shadow-xl">
                            <div class="flex gap-2 md:justify-evenly">
                                <div class="flex justify-center items-center space-x-2">
                                    <i class="fas fa-eye text-black cursor-pointer"></i>
                                    @if (
                                        $role != 'ppm' &&
                                            !Carbon::today()->lt(Carbon::parse($item->hard_periode_awal)) &&
                                            !Carbon::today()->gt(Carbon::parse($item->hard_periode_akhir)))
                                        <i class="fas fa-edit text-black cursor-pointer"></i>
                                    @endif
                                </div>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $desk->links() }}
    </div>
</div>
