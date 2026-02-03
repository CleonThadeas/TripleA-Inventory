<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-6">
        <h1 class="text-2xl font-semibold mb-6">Tambah Asset Baru</h1>

        @if ($errors->any())
        <div class="mb-4 rounded bg-red-100 p-4 text-red-700">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    

        <form method="POST"
              action="{{ route('assets.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf

            {{-- ================= INFO ASSET ================= --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Nama Asset</label>
                    <input type="text" name="name" required class="border p-2 w-full">
                </div>

                <div>
                    <label>Serial Code</label>
                    <input type="text"
                           name="serial_code"
                           required
                           class="border p-2 w-full"
                           placeholder="00123">
                </div>
                

                <div>
                    <label>Tahun Pembelian</label>
                    <input type="number"
                           name="purchase_year"
                           value="{{ date('Y') }}"
                           required
                           class="border p-2 w-full">
                </div>

                <div>
                    <label>Kategori</label>
                    <select name="category_id" required class="border p-2 w-full">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Lokasi</label>
                    <select name="location_id" required class="border p-2 w-full">
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Departemen</label>
                    <select name="department_id" required class="border p-2 w-full">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Pengguna Asset</label>
                    <input type="text" name="employee_name" class="border p-2 w-full">
                </div>

                <div>
                    <label>Brand</label>
                    <input type="text" name="brand" required class="border p-2 w-full">
                </div>

                <div>
                    <label>Model</label>
                    <input type="text" name="model" required class="border p-2 w-full">
                </div>
            </div>

            {{-- ================= FOTO ASSET ================= --}}
            <div class="space-y-2">
                <label class="font-medium">Foto Asset</label>

                <button type="button"
                        onclick="openUploadModal()"
                        class="border px-4 py-2 rounded bg-gray-100">
                    Upload Foto
                </button>

                <div id="photoPreview" class="hidden">
                    <img id="photoPreviewImg"
                         class="max-h-48 border rounded mt-2">
                </div>

                <input type="file"
                       id="cameraInput"
                       name="photo"
                       accept="image/*"
                       capture="environment"
                       class="hidden"
                       onchange="previewPhoto(this)">

                <input type="file"
                       id="galleryInput"
                       accept="image/*"
                       class="hidden"
                       onchange="copyFileToMain(this)">
            </div>

            {{-- MODAL --}}
            <div id="uploadModal"
                 class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                <div class="bg-white rounded-lg w-64 p-4 space-y-3">
                    <h3 class="font-semibold text-center">Upload Foto</h3>

                    <button type="button"
                            onclick="chooseCamera()"
                            class="w-full border py-2 rounded">
                        Kamera
                    </button>

                    <button type="button"
                            onclick="chooseGallery()"
                            class="w-full border py-2 rounded">
                        Pilih File
                    </button>

                    <button type="button"
                            onclick="closeUploadModal()"
                            class="w-full text-sm text-gray-500">
                        Batal
                    </button>
                </div>
            </div>

            <hr>

            {{-- ================= DESKRIPSI KOMPONEN ================= --}}
            <h3 class="font-semibold">Komponen (Deskripsi)</h3>
            <textarea name="components_description"
                      rows="5"
                      class="w-full border p-3"
                      placeholder="CPU: Intel i5
RAM: 16GB"></textarea>

            <hr>

            {{-- ================= MANUAL KOMPONEN ================= --}}
            <h3 class="font-semibold">Komponen (Manual)</h3>

            <div id="component-wrapper" class="space-y-2">
                <div class="flex gap-2">
                    <input type="text" name="components[0][key]" class="border p-2 flex-1">
                    <input type="text" name="components[0][value]" class="border p-2 flex-1">
                    <button type="button" onclick="removeComponent(this)">✕</button>
                </div>
            </div>

            <button type="button" onclick="addComponent()">+ Tambah Komponen</button>

            <hr>

            <div class="flex gap-4">
                <a href="{{ route('assets.view.index') }}">Batal</a>
                <button type="submit" class="border px-4 py-2">
                    Simpan Asset
                </button>
            </div>
        </form>
    </div>

<script>
let componentIndex = 1;

function addComponent() {
    const wrapper = document.getElementById('component-wrapper');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input type="text" name="components[${componentIndex}][key]" class="border p-2 flex-1">
        <input type="text" name="components[${componentIndex}][value]" class="border p-2 flex-1">
        <button type="button" onclick="removeComponent(this)">✕</button>
    `;
    wrapper.appendChild(div);
    componentIndex++;
}

function removeComponent(btn){
    btn.parentElement.remove();
}

function openUploadModal(){
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal(){
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
}

function chooseCamera(){
    closeUploadModal();
    document.getElementById('cameraInput').click();
}

function chooseGallery(){
    closeUploadModal();
    document.getElementById('galleryInput').click();
}

function copyFileToMain(input){
    if(!input.files[0]) return;
    const dt = new DataTransfer();
    dt.items.add(input.files[0]);
    const main = document.getElementById('cameraInput');
    main.files = dt.files;
    previewPhoto(main);
}

function previewPhoto(input){
    if(!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('photoPreview').classList.remove('hidden');
        document.getElementById('photoPreviewImg').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
</x-app-layout>
