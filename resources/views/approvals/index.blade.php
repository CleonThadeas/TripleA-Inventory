<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-6 space-y-10">

        {{-- ================= HEADER ================= --}}
        <div>
            <h1 class="text-2xl font-semibold">Approval Center</h1>
            <p class="text-gray-600 text-sm">
                Persetujuan Asset, Asset Group, dan Perubahan Data
            </p>
        </div>

        {{-- =========================================================
        | SECTION 1 — PENDING ASSET
        ========================================================= --}}
        <div class="space-y-3">
            <h2 class="text-lg font-semibold">🧾 Pending Asset</h2>

            <div class="overflow-x-auto bg-white border rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Kode</th>
                            <th class="px-3 py-2 text-left">Nama</th>
                            <th class="px-3 py-2 text-left">Kategori</th>
                            <th class="px-3 py-2 text-left">Dibuat Oleh</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingAssets as $asset)
                            <tr class="border-t">
                                <td class="px-3 py-2 font-mono">
                                    {{ $asset->asset_code }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $asset->name }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($asset->category)->name ?? '-' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($asset->creator)->name ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-center space-x-2">
                                    <a href="{{ route('approvals.assets.show', $asset) }}"
                                       class="text-blue-600 hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada asset menunggu approval
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- =========================================================
        | SECTION 2 — PENDING ASSET GROUP
        ========================================================= --}}
        <div class="space-y-3">
            <h2 class="text-lg font-semibold">📦 Pending Asset Group</h2>

            <div class="overflow-x-auto bg-white border rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Nama Group</th>
                            <th class="px-3 py-2 text-left">Pengguna</th>
                            <th class="px-3 py-2 text-left">Jumlah Asset</th>
                            <th class="px-3 py-2 text-left">Dibuat Oleh</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingGroups as $group)
                            <tr class="border-t">
                                <td class="px-3 py-2 font-semibold">
                                    {{ $group->name }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $group->employee_name ?? '-' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $group->assets->count() }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($group->creator)->name ?? '-' }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('approvals.groups.show', $group) }}"
                                       class="text-blue-600 hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada asset group menunggu approval
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- =========================================================
        | SECTION 3 — PENDING CHANGES (UPDATE REQUEST)
        ========================================================= --}}
        <div class="space-y-3">
            <h2 class="text-lg font-semibold">✏️ Pending Changes</h2>
            <p class="text-sm text-gray-500">
                Perubahan data asset / group oleh staff (menunggu approval)
            </p>

            <div class="overflow-x-auto bg-white border rounded">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Objek</th>
                            <th class="px-3 py-2 text-left">Aksi</th>
                            <th class="px-3 py-2 text-left">Oleh</th>
                            <th class="px-3 py-2 text-left">Waktu</th>
                            <th class="px-3 py-2 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingChanges as $log)
                            <tr class="border-t">
                                <td class="px-3 py-2">
                                    {{ class_basename($log->subject_type) }}
                                    <span class="font-mono text-xs text-gray-500">
                                        #{{ $log->subject_id }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 font-semibold">
                                    UPDATE
                                </td>
                                <td class="px-3 py-2">
                                    {{ optional($log->user)->name ?? 'System' }}
                                </td>
                                <td class="px-3 py-2">
                                    {{ $log->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td class="px-3 py-2 text-center">
                                    <a href="{{ route('approval.changes.show', $log) }}"
                                       class="text-blue-600 hover:underline">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada perubahan menunggu approval
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
