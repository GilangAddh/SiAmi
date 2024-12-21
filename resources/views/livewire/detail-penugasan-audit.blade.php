<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('penugasan-audit') }}">Penugasan Audit</a></li>
            <li>Penugasan Audit {{ $this->profile_name }}</li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Pemetaan Auditor {{ $this->profile_name }}</h1>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg mt-6">
        <table class="table table-zebra table-pin-cols">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <td class="text-center">No</td>
                    <td>NIP</td>
                    <td>Profil Auditor</td>
                    <th class="bg-[#60c0d0] shadow-xl text-center">Tugaskan Auditor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($auditor as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}.</td>
                        <td>{{ $item->no_identity }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="rounded-full h-12 w-12">
                                        <img
                                            src="{{ $item->profile_photo_path ? Storage::url($item->profile_photo_path) : asset('images/avatar.png') }}" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $item->profile_name }}</div>
                                    <div class="text-sm opacity-50">{{ $item->email }}</div>
                                </div>
                            </div>
                        </td>
                        <th class="shadow-xl text-center">
                            <input type="checkbox" class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]"
                                wire:model="selectedAuditor" value="{{ $item->id }}">
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
            href="{{ route('penugasan-audit') }}">Kembali</a>
        <button
            class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            wire:click="save">Simpan</button>
    </div>
</div>
