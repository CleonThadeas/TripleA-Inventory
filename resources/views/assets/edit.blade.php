<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-6 space-y-10">
    
        <h1 class="text-2xl font-semibold">Edit Asset</h1>
    
        <div class="border p-4 bg-gray-50">
            <p><b>Asset Code:</b> {{ $asset->asset_code }}</p>
            <p><b>Status:</b> {{ strtoupper($asset->status) }}</p>
        </div>
    
        {{-- ================= FORM UPDATE ASSET ================= --}}
        <form method="POST"
              action="{{ route('assets.update', $asset->asset_code) }}"
              enctype="multipart/form-data"
              class="border p-4 space-y-4">
            @csrf
            @method('PUT')
    
            <h3 class="font-semibold">Informasi Asset</h3>
    
            <div>
                <label>Nama Asset</label><br>
                <input type="text" name="name" value="{{ $asset->name }}" required>
            </div>
    
            <div>
                <label>Pengguna Asset</label><br>
                <input type="text" name="employee_name" value="{{ $asset->employee_name }}">
            </div>
    
            <div>
                <label>Brand</label><br>
                <input type="text" name="brand" value="{{ $asset->brand }}" required>
            </div>
    
            <div>
                <label>Model</label><br>
                <input type="text" name="model" value="{{ $asset->model }}" required>
            </div>
    
            <div>
                <label>Foto</label><br>
                <input type="file" name="photo">
                @if($asset->photo_path)
                    <br>
                    <img src="{{ asset('storage/'.$asset->photo_path) }}" width="150">
                @endif
            </div>
    
            <div class="flex justify-end">
                <button class="px-4 py-2 border">Simpan Asset</button>
            </div>
        </form>
    
        {{-- ================= DAFTAR COMPONENT ================= --}}
        <div class="border p-4 space-y-3">
            <h3 class="font-semibold">Komponen Asset</h3>
    
            @forelse($asset->components as $comp)
                <div class="flex items-center gap-3">
                    <div class="flex-1">
                        {{ $comp->component_key }} : {{ $comp->component_value }}
                    </div>
    
                    {{-- DELETE COMPONENT (FORM TERPISAH) --}}
                    <form method="POST"
                          action="{{ route('components.destroy', $comp->id) }}"
                          onsubmit="return confirm('Hapus komponen ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600">🗑</button>
                    </form>
                </div>
            @empty
                <p class="text-sm text-gray-500">Belum ada komponen</p>
            @endforelse
        </div>
    
        {{-- ================= TAMBAH COMPONENT BARU ================= --}}
        <form method="POST"
              action="{{ route('components.store') }}"
              class="border p-4 space-y-3">
            @csrf
    
            <input type="hidden" name="parent_type" value="asset">
            <input type="hidden" name="parent_id" value="{{ $asset->asset_code }}">
    
            <h3 class="font-semibold">Tambah Komponen Baru</h3>
    
            <div class="flex gap-2">
                <input name="component_key" placeholder="Key (contoh: RAM)" required>
                <input name="component_value" placeholder="Value (contoh: 16GB)" required>
            </div>
    
            <button class="px-4 py-2 border">
                Simpan Komponen
            </button>
        </form>
    
        {{-- ================= DELETE ASSET ================= --}}
        <div class="flex justify-end">
            <form method="POST"
                  action="{{ route('assets.destroy', $asset->asset_code) }}"
                  onsubmit="return confirm('Asset akan dihapus.\nHistory tetap disimpan.\n\nLanjutkan?')">
                @csrf
                @method('DELETE')
    
                <button class="text-red-700 font-bold">
                    HAPUS ASSET
                </button>
            </form>
        </div>
    
    </div>
    </x-app-layout>
    