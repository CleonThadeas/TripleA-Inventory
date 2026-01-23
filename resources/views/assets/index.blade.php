
<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-6 space-y-6">
    
        <h1 class="text-2xl font-semibold">Asset List</h1>
    
        {{-- ================= SEARCH BAR ================= --}}
        <form method="GET" class="flex gap-2">
            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   placeholder="Cari kode asset / nama / serial"
                   class="border px-3 py-2 w-full rounded">
    
            <button class="border px-4 rounded">Cari</button>
    
            {{-- FILTER BUTTON --}}
            <button type="button"
                    onclick="openFilterModal()"
                    class="border px-4 rounded">
                Filter
            </button>
    
            {{-- QR BUTTON --}}
            <button type="button"
                    onclick="openQrModal()"
                    class="border px-4 rounded">
                Scan QR
            </button>
        </form>
    
        {{-- ================= TABLE ================= --}}
        <table class="w-full border text-sm mt-4">
            <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Asset Code</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($assets as $asset)
                <tr class="border-t">
                    <td class="p-2 font-mono">{{ $asset->asset_code }}</td>
                    <td class="p-2">{{ $asset->name }}</td>
                    <td class="p-2">{{ strtoupper($asset->status) }}</td>
                    <td class="p-2 space-x-2">
                        <a href="{{ route('assets.view.show', $asset->asset_code) }}" class="text-blue-600">Detail</a>
                        <a href="{{ route('assets.view.history', $asset->asset_code) }}" class="text-gray-600">History</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="p-4 text-center">Tidak ada data</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    
        {{ $assets->links() }}
    
    </div>
    
    {{-- ================= FILTER MODAL ================= --}}
    <div id="filterModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
        <form method="GET" class="bg-white p-6 rounded space-y-4 w-full max-w-xl">
            <h3 class="font-semibold text-lg">Filter Asset</h3>
    
            <div class="grid grid-cols-2 gap-3">
                <select name="category_id" class="border p-2">
                    <option value="">Kategori</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
    
                <select name="location_id" class="border p-2">
                    <option value="">Lokasi</option>
                    @foreach($locations as $l)
                        <option value="{{ $l->id }}">{{ $l->name }}</option>
                    @endforeach
                </select>
    
                <select name="department_id" class="border p-2">
                    <option value="">Departemen</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
    
                <input type="number" name="purchase_year" placeholder="Tahun" class="border p-2">
    
                <select name="status" class="border p-2 col-span-2">
                    <option value="">Status</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
    
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeFilterModal()">Batal</button>
                <button class="border px-4 py-2">Terapkan</button>
            </div>
        </form>
    </div>
    
    {{-- ================= QR MODAL ================= --}}
    <div id="qrModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center">
        <div class="bg-white p-6 rounded space-y-4 w-full max-w-md">
    
            <h3 class="font-semibold text-lg">Scan QR Asset</h3>
    
            {{-- CAMERA --}}
            <div id="qr-reader" class="border"></div>
            <a href="{{ route('assets.qr.scan') }}"
            class="border px-4 py-2 rounded">
            📷 Scan QR
         </a>
         
            <hr>
    
            {{-- FILE --}}
            <input type="file" id="qrFile" accept="image/*" class="w-full">
            <button onclick="scanFile()" class="border px-4 py-2 w-full">
                OK (Scan Foto)
            </button>
    
            <button onclick="closeQrModal()" class="text-red-600 w-full">
                Tutup
            </button>
        </div>
    </div>
    
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr"></script>
    
    <script>
    let html5Qr;
    
    function openFilterModal(){
        document.getElementById('filterModal').classList.remove('hidden')
    }
    function closeFilterModal(){
        document.getElementById('filterModal').classList.add('hidden')
    }
    
    function openQrModal(){
        document.getElementById('qrModal').classList.remove('hidden')
    }
    function closeQrModal(){
        if(html5Qr){ html5Qr.stop() }
        document.getElementById('qrModal').classList.add('hidden')
    }
    
    function startCamera(){
        html5Qr = new Html5Qrcode("qr-reader");
        html5Qr.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            text => {
                window.location.href = "/assets-view/" + text;
            }
        );
    }
    
    function scanFile(){
        const file = document.getElementById('qrFile').files[0];
        if(!file){ alert("Pilih file terlebih dahulu"); return; }
    
        const reader = new FileReader();
        reader.onload = e => {
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img,0,0);
                const qr = jsQR(
                    ctx.getImageData(0,0,canvas.width,canvas.height).data,
                    canvas.width,
                    canvas.height
                );
                if(qr){
                    window.location.href = "/assets-view/" + qr.data;
                }else{
                    alert("QR tidak terbaca");
                }
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
    </script>
    </x-app-layout>
    