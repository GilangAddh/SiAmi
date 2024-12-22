@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Penugasan Audit</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('penugasan-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Jadwal Audit</h1>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Periode Audit</td>
                    <td>Unit Kerja</td>
                    <td class="text-center">Status Penugasan</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Penugasan Auditor</th>
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
                            <a href="{{ route('detail-penugasan-audit', ['periode' => $item->periodeAudit, 'unitKerja' => $item->unitKerja]) }}"
                                class="text-[#60c0d0]"><i class="fa-solid fa-code-branch w-4 h-4 mr-1"></i>Auditor</a>
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

</div>
