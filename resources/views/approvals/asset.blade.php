<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-xl font-semibold">
            Approval Asset
        </h1>

        <div class="bg-white border rounded-lg p-6 space-y-4">

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><b>Kode:</b> {{ $asset->asset_code }}</div>
                <div><b>Nama:</b> {{ $asset->name }}</div>
                <div><b>Kategori:</b> {{ $asset->category->name }}</div>
                <div><b>Lokasi:</b> {{ $asset->location->name }}</div>
                <div><b>Departemen:</b> {{ $asset->department->name }}</div>
                <div><b>Pemakai:</b> {{ $asset->employee_name ?? '-' }}</div>
                <div><b>Status:</b> {{ $asset->status }}</div>
                <div><b>Approval:</b> {{ $asset->approval_status }}</div>
            </div>

            <hr>

            <h3 class="font-semibold">Komponen</h3>

            <ul class="list-disc pl-5 text-sm">
                @forelse($asset->components as $c)
                    <li>{{ $c->component_key }} : {{ $c->component_value }}</li>
                @empty
                    <li class="text-gray-500">Tidak ada komponen</li>
                @endforelse
            </ul>

            <hr>

            <div class="flex justify-end gap-3">
                <form method="POST"
                      action="{{ route('approvals.assets.reject', $asset) }}">
                    @csrf
                    <button class="px-4 py-2 border rounded text-red-600">
                        Reject
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('approvals.assets.approve', $asset) }}">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded">
                        Approve
                    </button>
                </form>
            </div>

        </div>

    </div>
</x-app-layout>
