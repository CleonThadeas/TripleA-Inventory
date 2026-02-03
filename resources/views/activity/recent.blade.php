<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <h1 class="text-xl font-bold">
            Aktivitas Terakhir Sistem
        </h1>

        <p class="text-gray-600">
            Menampilkan aktivitas terbaru dari Asset & Asset Group
        </p>

        <hr>

        <table class="w-full border-collapse border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Waktu</th>
                    <th class="border px-3 py-2">Tipe</th>
                    <th class="border px-3 py-2">Kode / Nama</th>
                    <th class="border px-3 py-2">Aksi</th>
                    <th class="border px-3 py-2">User</th>
                    <th class="border px-3 py-2">Detail</th>
                </tr>
            </thead>

            <tbody>
            @forelse($activities as $log)
                @php
                    $type = class_basename($log->loggable_type);
                    $isAsset = $type === 'Asset';
                    $isGroup = $type === 'AssetGroup';
                    $loggable = $log->loggable;
                @endphp

                <tr>
                    {{-- WAKTU --}}
                    <td class="border px-3 py-2">
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                    </td>

                    {{-- TIPE --}}
                    <td class="border px-3 py-2">
                        @if($isAsset)
                            Single Asset
                        @elseif($isGroup)
                            Asset Group
                        @else
                            Lainnya
                        @endif
                    </td>

                    {{-- KODE / NAMA --}}
                    <td class="border px-3 py-2 font-mono">
                        @if($loggable)
                            {{ $log->loggable_code ?? $loggable->name ?? '—' }}
                        @else
                            <span class="italic text-gray-400">
                                Data telah dihapus
                            </span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="border px-3 py-2 font-semibold">
                        {{ strtoupper($log->action) }}
                    </td>

                    {{-- USER --}}
                    <td class="border px-3 py-2">
                        {{ optional($log->user)->name ?? 'System' }}
                    </td>

                    {{-- DETAIL --}}
                    <td class="border px-3 py-2">
                        @if($loggable && $isAsset)
                            <a href="{{ route('assets.view.show', $loggable->asset_code) }}"
                               class="text-blue-600 hover:underline">
                                Lihat Asset
                            </a>
                        @elseif($loggable && $isGroup)
                            <span class="text-gray-500 italic">
                                Asset Group
                            </span>
                        @else
                            <span class="text-gray-400 italic">
                                Tidak tersedia
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"
                        class="border px-3 py-4 text-center text-gray-500">
                        Tidak ada aktivitas
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <hr>

        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">
            ← Kembali ke Dashboard
        </a>

    </div>
</x-app-layout>
