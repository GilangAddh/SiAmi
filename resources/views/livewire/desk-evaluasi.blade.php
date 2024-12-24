<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Desk Evaluasi</li>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="/desk-evaluasi">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Desk Evaluasi</h1>

    <div class="flex justify-between my-6 items-center">
        {{-- @if ($role == 'ppm')
            <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm">
                Tambah
                <i class="fa-solid fa-plus"></i>
            </button>
        @endif --}}
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
                <tr>
                    <td class="text-center">
                        1.
                    </td>
                    <td>
                        01 Desember 2024 - 31 Desember 2024
                    </td>
                    <td>
                        Unit Kerja 1
                    </td>
                    <td>
                        ISO:9001
                    </td>
                    <td>
                        <ol class="list-decimal">
                            <li>Auditor 1</li>
                            <li>Auditor 2</li>
                        </ol>
                    </td>
                    <td>
                        <ul class="list-none">
                            <li class="mb-2">Sesuai Standar:<br><span class="text-[#60c0d0] font-medium">0/10
                                    Pernyataan</span></li>
                            <li class="mb-2">Observasi:<br><span class="text-[#60c0d0] font-medium">0/10
                                    Pernyataan</span></li>
                            <li>KTS:<br><span class="text-[#60c0d0] font-medium">0/10 Pernyataan</span></li>
                        </ul>
                    </td>
                    <td>
                        <ul class="list-none">
                            <li class="mb-2">Pengisian Evaluasi:<br><span class="text-[#60c0d0]">0/10 Pernyataan
                                    Dilengkapi</span>
                            </li>
                            <li>Audit Lapangan:<br><span class="text-[#60c0d0]">0/10 Pernyataan Dilengkapi</span></li>
                        </ul>
                    </td>
                    <td class="text-center text-error font-medium">
                        Belum Selesai
                    </td>
                    <th class="shadow-xl">
                        <div class="flex gap-2 md:justify-evenly">
                            {{-- <div>
                                @if ($role == 'auditor')
                                    <button class="btn btn-xs bg-[#60c0d0] text-white">Ajukan Selesai</button>
                                @endif
                                @if ($role == 'ppm')
                                    <button class="btn btn-xs bg-[#60c0d0] text-white">Setujui Selesai</button>
                                @endif
                            </div> --}}
                            <div class="flex justify-center items-center space-x-2">
                                <i class="fas fa-eye text-black cursor-pointer"></i>
                                @if ($role != 'ppm')
                                    <i class="fas fa-edit text-black cursor-pointer"></i>
                                @endif
                            </div>
                        </div>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
