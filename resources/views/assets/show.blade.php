<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">
        <h1 class="text-2xl font-semibold">
            Detail Asset
        </h1>

        <div class="bg-white shadow rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Asset Code</p>
                <p class="font-mono">{{ $asset->asset_code }}</p>

                <p class="mt-3 text-sm text-gray-500">Nama</p>
                <p>{{ $asset->name }}</p>

                <p class="mt-3 text-sm text-gray-500">Status</p>
                <span class="px-2 py-1 text-xs rounded bg-gray-100">
                    {{ strtoupper($asset->status) }}
                </span>
            </div>

            <div>
                <p><b>Lokasi:</b> {{ $asset->location->name }}</p>
                <p><b>Departemen:</b> {{ $asset->department->name }}</p>
                <p><b>Tahun:</b> {{ $asset->purchase_year }}</p>
                <p><b>Brand:</b> {{ $asset->brand }}</p>
                <p><b>Model:</b> {{ $asset->model }}</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold mb-2">Foto Asset</h3>
            @if($asset->photo_path)
                <img src="{{ asset('storage/'.$asset->photo_path) }}"
                     class="max-w-xs rounded border">
            @else
                <p class="text-gray-500">Tidak ada foto</p>
            @endif

            <h3>QR Code</h3>

            @if ($asset->qr_code_path)
                <img src="{{ asset('storage/' . $asset->qr_code_path) }}" width="200">
            @else
                <p class="text-gray-500">QR Code belum tersedia</p>
            
                @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('assets.qr', $asset->asset_code, $asset->id) }}">
                        @csrf
                        <button class="border px-3 py-1">
                            Generate QR Manual
                        </button>
                    </form>
                @endif
            @endif
            
        </div>
        

        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold mb-2">Komponen</h3>
            @if($asset->components->isEmpty())
                <p class="text-gray-500">Belum ada komponen</p>
            @else
                <ul class="list-disc pl-5">
                    @foreach($asset->components as $c)
                        <li>{{ $c->component_key }} : {{ $c->component_value }}</li>
                    @endforeach
                </ul>
            @endif
            <h3>Activity Log</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                </tr>
                @foreach ($asset->activities as $log)
                    <tr>
                        <td>{{ $log->created_at }}</td>
                        <td>{{ $log->user->name ?? 'System' }}</td>
                        <td>{{ strtoupper($log->action) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('assets.view.index') }}"
               class="text-gray-600 hover:underline">
                ← Kembali
            </a>

        </div>
    </div>
</x-app-layout>
