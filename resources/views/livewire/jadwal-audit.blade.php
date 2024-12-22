@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Jadwal Audit</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('jadwal-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Penjadwalan Periode Audit</h1>

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
        </div>

        {{-- <select class="select select-bordered w-full md:w-1/6" wire:model.live="filter">
            <option value="semua" selected>Semua Jadwal</option>
            <option value="terjadwal" selected>Terjadwal</option>
            <option value="belum-terjadwal" selected>Belum Terjadwal</option>
        </select> --}}
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Periode Audit</td>
                    <td>Status Periode</td>
                    <td class="text-center">Status Penjadwalan</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Kelola Jadwal Audit</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($periode as $index => $item)
                    @php
                        $tanggalMulai = Carbon::parse($item->tanggal_mulai);
                        $tanggalAkhir = Carbon::parse($item->tanggal_akhir);
                        $today = Carbon::today();
                        $status = '';

                        if ($today->lt($tanggalMulai)) {
                            $daysUntilStart = $today->diffInDays($tanggalMulai);
                            $status = "Menunggu dimulai: $daysUntilStart hari lagi";
                        } elseif ($today->between($tanggalMulai, $tanggalAkhir)) {
                            $status = 'Periode berlangsung';
                        } elseif ($today->gt($tanggalAkhir)) {
                            $status = 'Periode berakhir';
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ Carbon::parse($item->tanggal_mulai)->locale('id')->translatedFormat('d F Y') }} -
                            {{ Carbon::parse($item->tanggal_akhir)->locale('id')->translatedFormat('d F Y') }}</td>

                        <td>{{ $status }}</td>

                        <td class="text-center">
                            @if ($item->unit_kerja_count > 0)
                                <span class="font-semibold text-[#60c0d0]">Terjadwal</span>
                            @else
                                <span class="font-semibold text-error">Belum Terjadwal</span>
                            @endif
                            <br>{{ $item->unit_kerja_count }}
                            Unit Kerja Dijadwalkan untuk Audit
                        </td>

                        <th class="shadow-xl text-center">
                            <a href="{{ route('jadwal-unit-kerja', ['periode' => $item]) }}" class="text-[#60c0d0]"><i
                                    class="fa-solid fa-calendar-days w-4 h-4 mr-1"></i>
                                Jadwal</a>
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

    <div class="mt-4">
        {{ $periode->links() }}
    </div>

</div>
