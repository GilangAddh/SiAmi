@section('title', $title)

@php
    use Carbon\Carbon;
@endphp

<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li>Standar Audit</li>
            <li><a class="text-[#60C0D0] text-medium" href="{{ route('standar-audit') }}">Index</a></li>
        </ul>
    </div>

    <h1 class="font-bold text-2xl">Data Standar Audit</h1>

    <div class="flex justify-between my-6 items-center flex-wrap">
        <label class="input input-bordered flex items-center input-sm py-5 pr-4 pl-1 w-full max-w-[350px] sm:w-3/5 md:w-1/4">
            <input wire:model.debounce.300ms="search" type="text" class="grow border-none text-sm gap-2 w-full"
                placeholder="Cari" wire:model.live.debounce.500ms="search"/>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-5 opacity-70">
                <path fill-rule="evenodd"
                    d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                    clip-rule="evenodd" />
            </svg>
        </label>

        <button class="btn text-white btn-sm bg-[#60c0d0] border-none px-3 text-sm">
            Tambah
            <i class="fa-solid fa-plus"></i>
        </button>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md">
                    <th class="text-center">No</th>
                    <th>Nama Standar</th>
                    <th class="text-center">Nomor Dokumen</th>
                    <th>Nomer Revisi</th>
                    <th>Tanggal Terbit</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($standar as $index => $item)
                    <tr>
                        <td class="text-center">{{$index + 1}}</td>
                        <td>
                            {{$item->nama_standar}}
                        </td>
                        <td>
                            {{$item->nomer_dokumen}}
                        </td>
                        <td>
                            {{$item->nomer_revisi}}
                        </td>
                        <td>
                            {{$item->tanggal_terbit}}
                        </td>                        
                        <th class="text-center">
                            <div class="flex justify-center items-center space-x-2">
                                <i class="fas fa-eye text-black"></i>
                                <i class="fas fa-edit text-black"></i>
                                <button wire:click="delete({{ $item->id }})"><i class="fas fa-trash text-black"></i></button>
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
        {{ $standar->links('vendor.pagination.tailwind') }}
    </div>
</div>
