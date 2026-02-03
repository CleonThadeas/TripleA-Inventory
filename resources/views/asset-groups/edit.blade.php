<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">
    
        {{-- ================= HEADER ================= --}}
        <div>
            <h1 class="text-2xl font-semibold">Edit Asset Group</h1>
            <p class="text-sm text-gray-600">
                Kelola informasi group dan asset yang tergabung di dalamnya
            </p>
        </div>
    
        {{-- ================= GROUP SUMMARY ================= --}}
        <div class="border rounded p-4 bg-gray-50">
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Nama Group</p>
                    <p class="font-semibold">{{ $group->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Pengguna Asset</p>
                    <p class="font-semibold">{{ $group->employee_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Total Asset</p>
                    <p class="font-semibold">{{ $currentAssets->count() }}</p>
                </div>
            </div>
        </div>
    
        {{-- ================= FORM ================= --}}
        <form method="POST" action="{{ route('asset-groups.update', $group) }}" class="space-y-6">
            @csrf
            @method('PUT')
    
            {{-- ================= GROUP INFO ================= --}}
            <div class="space-y-3">
                <h3 class="font-semibold">Informasi Group</h3>
    
                <input type="text"
                       name="name"
                       value="{{ old('name', $group->name) }}"
                       class="border p-2 w-full"
                       placeholder="Nama Group">
    
                <input type="text"
                       name="employee_name"
                       value="{{ old('employee_name', $group->employee_name) }}"
                       class="border p-2 w-full"
                       placeholder="Nama Pengguna Asset">
            </div>
    
            {{-- ================= CURRENT ASSETS ================= --}}
            <div class="space-y-3">
                <h3 class="font-semibold">Asset dalam Group</h3>
    
                @forelse($currentAssets as $asset)
                    <div class="flex items-center justify-between border rounded p-3 bg-blue-50">
                        <label class="flex items-center gap-3">
                            <input type="checkbox"
                                   name="asset_ids[]"
                                   value="{{ $asset->id }}"
                                   checked>
    
                            <div>
                                <p class="font-mono text-sm">{{ $asset->asset_code }}</p>
                                <p class="text-sm text-gray-700">{{ $asset->name }}</p>
                            </div>
                        </label>
    
                        <a href="{{ route('assets.view.show', $asset->asset_code) }}"
                           target="_blank"
                           class="text-blue-600 text-sm">
                            Lihat Detail
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">
                        Belum ada asset di dalam group ini.
                    </p>
                @endforelse
            </div>
    
            {{-- ================= AVAILABLE ASSETS ================= --}}
            <div class="space-y-3">
                <h3 class="font-semibold">
                    Tambah Asset Baru
                    <span class="text-sm text-gray-500">(Status ACTIVE & belum tergabung)</span>
                </h3>
    
                @forelse($availableAssets as $asset)
                    <div class="flex items-center justify-between border rounded p-3">
                        <label class="flex items-center gap-3">
                            <input type="checkbox"
                                   name="asset_ids[]"
                                   value="{{ $asset->id }}">
    
                            <div>
                                <p class="font-mono text-sm">{{ $asset->asset_code }}</p>
                                <p class="text-sm text-gray-700">{{ $asset->name }}</p>
                            </div>
                        </label>
    
                        <a href="{{ route('assets.view.show', $asset->asset_code) }}"
                           target="_blank"
                           class="text-gray-600 text-sm">
                            Detail
                        </a>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">
                        Tidak ada asset aktif yang bisa ditambahkan.
                    </p>
                @endforelse
            </div>
    
            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-4 pt-4 border-t">
                <a href="{{ route('assets.view.index') }}"
                   class="text-gray-600">
                    Batal
                </a>
    
                <button type="submit"
                        class="bg-blue-600 px-4 py-2 rounded">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    
    </div>
    </x-app-layout>
    