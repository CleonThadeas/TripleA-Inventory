<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">
                Asset List
            </h1>

            <p>
                <a href="{{ route('assets.view.create') }}">+ Tambah Asset</a>
            </p>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Asset Code</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($assets as $asset)
                        <tr class="border-t">
                            <td class="px-4 py-3 font-mono">
                                {{ $asset->asset_code }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $asset->name }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $asset->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ strtoupper($asset->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 space-x-2">
                                <a href="{{ route('assets.view.show', $asset->id) }}"
                                   class="text-blue-600 hover:underline">
                                    Detail
                                </a>

                                <a href="{{ route('assets.view.history', $asset->id) }}"
                                    class="text-blue-600 hover:underline">
                                     History
                                 </a>
<hr>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('assets.view.edit', $asset->id) }}"
                                       class="text-gray-600 hover:underline">
                                        Edit Asset
                                    </a>
                                    <a href="{{ route('assets.audit.view', $asset->id) }}"
                                        class="text-gray-600 hover:underline">
                                         Audit Asset
                                     </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada asset.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
