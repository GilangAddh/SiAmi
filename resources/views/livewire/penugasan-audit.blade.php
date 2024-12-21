<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Penugasan Audit</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('penugasan-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Jadwal Audit</h1>

    <div class="flex justify-between my-6 items-center">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-3/5 md:w-1/4">
            <input wire:model.live.debounce.400ms="search" type="text"
                class="focus:outline-none focus:ring-0 grow border-none text-sm gap-2 w-full" placeholder="Cari" />
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>Jadwal Audit</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Penugasan Auditor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($unit as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>

                        <td>{{ $item->profile_name }}</td>
                        <th class="shadow-xl text-center">
                            <a href="{{ route('detail-penugasan-audit', ['unitKerja' => $item]) }}"
                                class="text-[#60c0d0]"><i class="fa-solid fa-code-branch w-4 h-4 mr-1"></i> Kelola
                                Auditor</a>
                        </th>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $unit->links() }}
    </div>

</div>
