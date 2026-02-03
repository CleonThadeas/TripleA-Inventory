<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-2xl font-semibold">
            Approval Perubahan Data
        </h1>

        <p class="text-gray-600">
            Daftar perubahan Asset & Asset Group yang menunggu persetujuan admin
        </p>

        <div class="bg-white border rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">Waktu</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="border-t">
                            <td class="px-4 py-3">
                                {{ $log->created_at->format('Y-m-d H:i') }}
                            </td>

                            <td class="px-4 py-3">
                                {{ class_basename($log->subject_type) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $log->subject->name ?? 'Data tidak tersedia' }}
                            </td>

                            <td class="px-4 py-3 font-semibold">
                                {{ strtoupper($log->action) }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $log->causer->name ?? 'System' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('approval.changes.show', $log) }}"
                                   class="text-blue-600 hover:underline">
                                    Lihat
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="px-4 py-6 text-center text-gray-500">
                                Tidak ada perubahan menunggu approval
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <a href="{{ route('dashboard') }}"
           class="text-blue-600 hover:underline">
            ← Kembali ke Dashboard
        </a>

    </div>
</x-app-layout>
