<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-6 space-y-6">
    
        {{-- ================= HEADER ================= --}}
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Asset List</h1>
    
            <div class="flex gap-2">
                <a href="{{ route('assets.view.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600">
                    ➕ Tambah Asset
                </a>
    
                <button type="button"
                        onclick="openGroupModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600">
                    📦 Buat Group
                </button>
            </div>
        </div>
    
        {{-- ================= SEARCH BAR ================= --}}
        <form method="GET" class="flex gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Cari kode asset / nama / serial"
                   class="border px-3 py-2 w-full rounded">
    
            <button class="border px-4 rounded">Cari</button>
    
            <button type="button" onclick="openFilterModal()" class="border px-4 rounded">
                Filter
            </button>
    
            <button type="button" onclick="openQrModal()" class="border px-4 rounded">
                Scan QR
            </button>
        </form>
    
        {{-- ================= TABLE ASSET ================= --}}
        <table class="w-full border text-sm mt-4">
            <thead class="bg-gray-100">
            <tr>
                <th class="p-2 w-10 text-center">
                    <input type="checkbox" onclick="toggleAll(this)">
                </th>
                <th class="p-2">Asset Code</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
            </thead>
    
            <tbody>
    {{-- ================= ASSET GROUP ================= --}}
@foreach($groups as $group)
<tr class="bg-blue-50 border-t">
    <td colspan="5" class="p-3 font-semibold flex justify-between items-center">
        <div>
            📦 GROUP: {{ $group->name }}
            <span class="text-sm text-gray-600">
                ({{ $group->employee_name }} • {{ $group->assets->count() }} asset)
            </span>
        </div>

        <div class="flex gap-3">
            {{-- EDIT GROUP --}}
            <a href="{{ route('asset-groups.edit', $group) }}"
               class="text-blue-600 text-sm">
                Edit
            </a>

            {{-- DELETE GROUP --}}
            <form method="POST"
                  action="{{ route('asset-groups.destroy', $group) }}"
                  onsubmit="return confirm('Hapus group ini?')">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="text-red-600 text-sm">
                    Hapus
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- ASSET DI DALAM GROUP --}}
@foreach($group->assets as $asset)
    <tr class="border-t bg-blue-50/30">
        <td></td>
        <td class="p-2 font-mono">{{ $asset->asset_code }}</td>
        <td class="p-2">{{ $asset->name }}</td>
        <td class="p-2">{{ strtoupper($asset->status) }}</td>
        <td class="p-2 space-x-2">
            <a href="{{ route('assets.view.show', $asset) }}"
               class="text-blue-600">
                Detail
            </a>
            <a href="{{ route('assets.view.history', $asset) }}"
               class="text-gray-600">
                History
            </a>
            <a href="{{ route('assets.view.edit', $asset) }}"
               class="text-gray-600">
                Edit
            </a>
        </td>
    </tr>
@endforeach
@endforeach

    
            {{-- ================= SINGLE ASSET ================= --}}
            @foreach($assets as $asset)
                @if($asset->groups->isEmpty())
                    <tr class="border-t">
                        <td class="text-center">
                            <input type="checkbox"
                                   class="asset-checkbox"
                                   value="{{ $asset->id }}">
                        </td>
                        <td class="p-2 font-mono">{{ $asset->asset_code }}</td>
                        <td class="p-2">{{ $asset->name }}</td>
                        <td class="p-2">{{ strtoupper($asset->status) }}</td>
                        <td class="p-2 space-x-2">
                            <a href="{{ route('assets.view.show', $asset->asset_code) }}" class="text-blue-600">Detail</a>
                            <a href="{{ route('assets.view.history', $asset->asset_code) }}" class="text-gray-600">History</a>
                            <a href="{{ route('assets.view.edit', $asset->asset_code) }}" class="text-gray-600">Edit</a>
                            <a href="{{ route('assets.audit.view', $asset->asset_code) }}" class="text-gray-600">Audit</a>
                        </td>
                    </tr>
                @endif
            @endforeach
    
            </tbody>
        </table>
    
        {{ $assets->links() }}
    </div>
    
    {{-- ================= FORM CREATE GROUP (TERPISAH TOTAL) ================= --}}
    <form id="groupForm" method="POST" action="{{ route('asset-groups.store') }}">
        @csrf
    
        <input type="hidden" name="name" id="group_name">
        <input type="hidden" name="employee_name" id="group_employee">
        <div id="group_asset_inputs"></div>
    </form>
    
    {{-- ================= MODAL BUAT GROUP ================= --}}
    <div id="groupModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded w-full max-w-md space-y-4">
            <h3 class="font-semibold text-lg">Buat Asset Group</h3>
    
            <input type="text" id="modal_group_name" placeholder="Nama Group" class="border p-2 w-full">
            <input type="text" id="modal_employee_name" placeholder="Nama Pengguna Asset" class="border p-2 w-full">
    
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeGroupModal()">Batal</button>
                <button type="button" onclick="submitGroup()" class="bg-green-600 text-white px-4 py-2 rounded">
                    Simpan Group
                </button>
            </div>
        </div>
    </div>
    
    <script>
    function toggleAll(source){
        document.querySelectorAll('.asset-checkbox').forEach(cb => cb.checked = source.checked);
    }
    
    function openGroupModal(){
        const checked = document.querySelectorAll('.asset-checkbox:checked');
        if(checked.length === 0){
            alert('Pilih minimal 1 asset');
            return;
        }
        document.getElementById('groupModal').classList.remove('hidden');
    }
    
    function closeGroupModal(){
        document.getElementById('groupModal').classList.add('hidden');
    }
    
    function submitGroup(){
        const name = document.getElementById('modal_group_name').value;
        const employee = document.getElementById('modal_employee_name').value;
    
        if(!name || !employee){
            alert('Lengkapi data group');
            return;
        }
    
        document.getElementById('group_name').value = name;
        document.getElementById('group_employee').value = employee;
    
        const container = document.getElementById('group_asset_inputs');
        container.innerHTML = '';
    
        document.querySelectorAll('.asset-checkbox:checked').forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'asset_ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });
    
        document.getElementById('groupForm').submit();
    }
    </script>
    </x-app-layout>
    