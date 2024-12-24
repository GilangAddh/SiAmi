<div class="px-5 py-4">
    <div class="breadcrumbs text-md">
        <ul>
            <li><a wire:navigate class="text-[#60C0D0] text-medium" href="/desk-evaluasi">Desk Evaluasi</a></li>
            <li>{{ ucfirst($action) }}</li>
        </ul>
    </div>
    <h1 class="font-bold text-2xl">Daftar Tilik Audit Mutu Internal</h1>

    <div class="mt-6 mb-5">
        <p class="mb-3">Standar: <span class="font-semibold">{{ $desk->hard_standar }}</span></p>
        <p class="mb-3">Unit Kerja: <span class="font-semibold">{{ $desk->hard_unit }}</span></p>
        <p class="mb-3">Hari/Tanggal Pengisian Desk Evaluasi: <span
                class="font-semibold">{{ $desk->tanggal_mulai_evaluasi ?? '-' }}</span>
        </p>
        <p class="mb-3">Hari/Tanggal Audit Lapangan: <span
                class="font-semibold">{{ $desk->tanggal_mulai_audit ?? '-' }}</span></p>
    </div>

    <div class="overflow-x-auto overflow-y-hidden border border-1 rounded-lg">
        <table class="table table-zebra">
            <thead class="bg-[#60c0d0] text-white font-bold">
                <tr class="text-md text-center">
                    <td>No</td>
                    <td>Pernyataan Standar</td>
                    <td>Indikator Standar</td>
                    <td>Pertanyaan/Pernyataan</td>
                    <td>Auditee</td>
                    <td>Bukti Objektif</td>
                    <td>Bukti Evaluasi Diri</td>
                    <td>Evaluasi Diri</td>
                    <td>Validasi Bukti Kerja</td>
                    <td>Pertanyaan Tambahan<br>Audit Lapangan</td>
                    <td>Kategori Temuan</td>
                    <td>Hasil Audit</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail as $index => $item)
                    <tr wire:key="{{ $item->id }}">
                        <td class="align-top text-center">{{ $index + 1 }}.</td>
                        <td class="max-w-64 align-top text-justify">
                            {{ $item->pernyataan }}
                        </td>
                        <td class="max-w-52 align-top text-justify">
                            @if (!empty($item->indikator))
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->indikator as $indikatorItem)
                                        <li class="mb-2">{{ $indikatorItem }}</li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="align-top max-w-64 text-justify">
                            @if (!empty($item->pertanyaan))
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->pertanyaan as $pertanyaanItem)
                                        <li class="mb-2">{{ $pertanyaanItem }}</li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="align-top max-w-36 text-left">
                            @if (!empty($item->auditee))
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->auditee as $auditeeItem)
                                        <li class="mb-2">
                                            {{ $auditeeItem }}
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="align-top max-w-36 text-left">
                            @if (!empty($item->bukti_objektif))
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->bukti_objektif as $bukti_obj)
                                        <li class="mb-2">
                                            {{ $bukti_obj }}
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="align-top max-w-48 text-left">
                            @if (!empty($item->bukti_evaluasi))
                                <ol class="list-none pl-5">
                                    @foreach ($item->bukti_evaluasi as $bukti_eval)
                                        <li class="mb-2">
                                            <a class="link link-hover underline hover:text-[#60c0d0]"
                                                href="{{ asset('storage/' . $bukti_eval) }}" target="_blank">
                                                <i class="fa-solid fa-file text-black"></i>
                                                <span class="ml-2">
                                                    {{ $bukti_eval }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="max-w-64 align-top text-justify">
                            @if ($action == 'view' || !$is_auditee)
                                {{ $item->evaluasi_diri }}
                            @else
                                <textarea class="textarea textarea-bordered textarea-md max-w-xs h-36"></textarea>
                            @endif
                        </td>
                        <td class="max-w-52 align-top">
                            @if (!empty($item->bukti_objektif))
                                @foreach ($item->bukti_objektif as $bukti_obj)
                                    <div class="form-control">
                                        <label class="label cursor-pointer flex justify-start items-center space-x-2">
                                            <input @if (!$is_auditor || $action == 'view') disabled @endif type="checkbox"
                                                class="checkbox [--chkbg:#60c0d0] [--chkfg:#ffffff]">
                                            <span class="label-text">{{ $bukti_obj }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        </td>
                        <td class="align-top max-w-64 text-justify">
                            @if (!empty($item->pertanyaan_tambahan))
                                <ol class="list-decimal pl-5">
                                    @foreach ($item->pertanyaan_tambahan as $pertanyaanNewItem)
                                        <li class="mb-2">{{ $pertanyaanNewItem }}</li>
                                    @endforeach
                                </ol>
                            @endif
                        </td>
                        <td class="align-top max-w-48 text-center">
                            @if ($action == 'view' || !$is_auditor)
                                {{ $item->kategori_temuan }}
                            @else
                                <select class="select select-bordered select-md">
                                    <option selected disabled>Pilih kategori</option>
                                    <option value="Sesuai Standar">Sesuai Standar</option>
                                    <option value="Observasi">Observasi</option>
                                    <option value="KTS">KTS</option>
                                </select>
                            @endif
                        </td>
                        <td class="max-w-68 align-top text-justify">
                            @if ($action == 'view' || !$is_auditor)
                                {{ $item->hasil_audit }}
                            @else
                                <textarea class="textarea textarea-bordered textarea-md max-w-xs h-36"></textarea>
                            @endif
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td colspan="6">Terakhir Diperbarui Oleh</td>
                        <td colspan="2">Auditee: @if ($item->latest_update_by_auditee && $item->latest_update_time_auditee)
                                {{ $item->latest_update_by_auditee }} ({{ $item->latest_update_by_auditee }} )
                            @else
                                -
                            @endif
                        </td>
                        <td colspan="4">Auditor: @if ($item->latest_update_by_auditor && $item->latest_update_time_auditor)
                                {{ $item->latest_update_by_auditor }} ({{ $item->latest_update_time_auditor }})
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <label class="form-control">
            <div class="label">
                <span class="label-text">Catatan Auditor</span>
            </div>
            <textarea @if (!$is_auditor || $action == 'view') disabled @endif class="textarea textarea-bordered textarea-md w-full h-24"></textarea>
        </label>
    </div>

    <div class="my-8 flex justify-between">
        <a class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none"
            wire:navigate href="/desk-evaluasi">Kembali</a>

        @if ($action != 'view')
            <button
                class="btn btn-sm btn-outline text-[#60c0d0] border-[#60c0d0] hover:bg-[#60c0d0] hover:text-white
            hover:border-none">Simpan</button>
        @endif
    </div>
</div>
