@section('title', $title)

@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Manajemen Pengguna</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('manajemen-pengguna') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Akun Pengguna</h1>

    <div class="flex justify-between my-6 items-center">
        {{-- <input type="text" placeholder="Search users..." wire:model.debounce.300ms="search"
            class="w-1/3 p-2 border border-gray-300 rounded-md mb-4" /> --}}

        <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3">
            Tambah Akun
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <th class="text-center">No</th>
                    <th>Identitas Akun</th>
                    <th class="text-center">Peran</th>
                    <th class="text-center">Waktu Pembuatan Akun</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr>
                        <td class="text-center">{{ $users->firstItem() + $index }}.</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar">
                                    <div class="mask mask-squircle h-12 w-12">
                                        <img src="{{ $user->profile_photo_path ? $user->profile_photo_path : asset('images/avatar.png') }}"
                                            alt="Avatar Tailwind CSS Component" />
                                    </div>
                                </div>
                                <div>
                                    <div class="font-bold">{{ $user->name }}</div>
                                    <div class="text-sm opacity-50">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="badge bg-[#60C0D0] p-3 text-white border-none">{{ $user->role }}</div>
                        </td>
                        <td class="text-center">
                            {{ Carbon::parse($user->created_at)->setTimezone('Asia/Jakarta')->translatedFormat('d F Y H:i') }}
                        </td>
                        <th class="text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <i class="fas fa-eye text-black"></i>
                                <i class="fas fa-edit text-black"></i>
                                <i class="fas fa-trash text-black"></i>
                            </div>
                        </th>
                    </tr>
                @empty
                    <tr class="hover">
                        <td colspan="5" class="text-center">Tidak ada data yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links('vendor.pagination.tailwind') }}
    </div>
</div>
