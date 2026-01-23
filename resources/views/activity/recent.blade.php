<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-xl font-bold">
            Aktivitas Terakhir Sistem
        </h1>

        <p class="text-gray-600">
            Menampilkan aktivitas terbaru dari Single Asset & Asset Package
        </p>

        <hr>

        <table class="w-full border-collapse border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Waktu</th>
                    <th class="border px-3 py-2">Tipe</th>
                    <th class="border px-3 py-2">Kode</th>
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
                    $loggable = $log->loggable;
                @endphp

                <tr>
                    <td class="border px-3 py-2">
                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                    </td>

                    <td class="border px-3 py-2">
                        {{ $isAsset ? 'Single Asset' : 'Asset Package' }}
                    </td>

                    <td class="border px-3 py-2 font-mono">
                        {{ optional($loggable)->asset_code
                            ?? optional($loggable)->package_code
                            ?? '— (Data Dihapus)' }}
                    </td>

                    <td class="border px-3 py-2 font-semibold">
                        {{ strtoupper($log->action) }}
                    </td>

                    <td class="border px-3 py-2">
                        {{ optional($log->user)->name ?? 'System' }}
                    </td>

                    <td class="border px-3 py-2">
                        @if($loggable)
                            @if($isAsset)
                                <a href="{{ route('assets.view.show', $loggable->asset_code) }}"
                                   class="text-blue-600 hover:underline">
                                    Lihat Asset
                                </a>
                            @else
                                <a href="{{ route('packages.view.show', $loggable->package_code) }}"
                                   class="text-blue-600 hover:underline">
                                    Lihat Package
                                </a>
                            @endif
                        @else
                            <span class="text-gray-400 italic">
                                Asset / Package sudah dihapus
                            </span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border px-3 py-4 text-center text-gray-500">
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
