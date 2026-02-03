<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-xl font-semibold">
            Approval Asset Group
        </h1>

        <div class="bg-white border rounded-lg p-6 space-y-4">

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><b>Nama Group:</b> {{ $group->name }}</div>
                <div><b>Pemakai:</b> {{ $group->employee_name ?? '-' }}</div>
                <div><b>Status Approval:</b> {{ $group->approval_status }}</div>
                <div><b>Dibuat Oleh:</b> {{ $group->creator->name ?? '-' }}</div>
            </div>

            <hr>

            <h3 class="font-semibold">
                Asset dalam Group
            </h3>

            <table class="w-full text-sm border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2">Kode</th>
                        <th class="px-3 py-2">Nama</th>
                        <th class="px-3 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($group->assets as $asset)
                    <tr class="border-t">
                        <td class="px-3 py-2 font-mono">
                            {{ $asset->asset_code }}
                        </td>
                        <td class="px-3 py-2">
                            {{ $asset->name }}
                        </td>
                        <td class="px-3 py-2">
                            {{ $asset->status }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <hr>

            <div class="flex justify-end gap-3">
                <form method="POST"
                      action="{{ route('approvals.groups.reject', $group) }}">
                    @csrf
                    <button class="px-4 py-2 border rounded text-red-600">
                        Reject
                    </button>
                </form>

                <form method="POST"
                      action="{{ route('approvals.groups.approve', $group) }}">
                    @csrf
                    <button class="px-4 py-2 bg-green-600 text-white rounded">
                        Approve
                    </button>
                </form>
            </div>

        </div>

    </div>
</x-app-layout>
