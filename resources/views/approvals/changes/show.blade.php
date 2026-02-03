<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-2xl font-semibold">
            Detail Perubahan Data
        </h1>

        <div class="bg-white border rounded-lg p-6 space-y-4">

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <strong>Tipe:</strong>
                    {{ class_basename($log->subject_type) }}
                </div>

                <div>
                    <strong>Nama:</strong>
                    {{ $log->subject->name ?? 'Data tidak tersedia' }}
                </div>

                <div>
                    <strong>Aksi:</strong>
                    {{ strtoupper($log->action) }}
                </div>

                <div>
                    <strong>User:</strong>
                    {{ $log->causer->name ?? 'System' }}
                </div>

                <div>
                    <strong>Waktu:</strong>
                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                </div>
            </div>

            <hr>

            {{-- PERUBAHAN --}}
            <h3 class="font-semibold text-lg">
                Ringkasan Perubahan
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-sm border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">Field</th>
                            <th class="px-3 py-2 border">Sebelum</th>
                            <th class="px-3 py-2 border">Sesudah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($log->changes ?? [] as $field => $change)
                            <tr>
                                <td class="px-3 py-2 border font-mono">
                                    {{ $field }}
                                </td>
                                <td class="px-3 py-2 border text-red-600">
                                    {{ $change['before'] ?? '-' }}
                                </td>
                                <td class="px-3 py-2 border text-green-600">
                                    {{ $change['after'] ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <hr>

            {{-- ACTION --}}
            <div class="flex justify-end gap-4">

                <form method="POST"
                      action="{{ route('approval.changes.reject', $log) }}">
                    @csrf
                    <button class="px-4 py-2 border rounded text-red-600">
                        Tolak
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('approval.changes.approve', $log) }}">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded">
                        Setujui
                    </button>
                </form>

            </div>
        </div>

        <a href="{{ route('approval.changes') }}"
           class="text-blue-600 hover:underline">
            ← Kembali ke Daftar
        </a>

    </div>
</x-app-layout>
