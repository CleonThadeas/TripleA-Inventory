<x-app-layout>
    <div class="max-w-4xl mx-auto px-6 py-6">
        <h1 class="text-2xl font-semibold mb-6">Tambah Asset Baru</h1>
    
        <form method="POST"
              action="{{ route('assets.store') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
    
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label>Nama Asset</label>
                    <input type="text" name="name" required>
                </div>
    
                <div>
                    <label>Serial Kode</label>
                    <input type="text"
                           name="serial_code"
                           required
                           oninput="this.value = this.value.toUpperCase()">
                </div>
    
                <div>
                    <label>Tahun Pembelian</label>
                    <input type="number" name="purchase_year"
                           value="{{ date('Y') }}" required>
                </div>
    
                <div>
                    <label>Kategori</label>
                    <select name="category_id" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div>
                    <label>Lokasi</label>
                    <select name="location_id" required>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div>
                    <label>Departemen</label>
                    <select name="department_id" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div>
                    <label>Pengguna Asset</label>
                    <input type="text" name="employee_name">
                </div>
    
                <div>
                    <label>Brand</label>
                    <input type="text" name="brand" required>
                </div>
    
                <div>
                    <label>Model</label>
                    <input type="text" name="model" required>
                </div>
            </div>
    
            <div>
                <label>Foto Asset</label>
                <input type="file" name="photo">
            </div>
    
            <hr>
    
            <h3 class="font-semibold">Komponen Asset</h3>
            <p class="text-sm">Maksimal 10 komponen</p>
    
            <div id="component-wrapper" class="space-y-2">
                <div class="component-row flex gap-2">
                    <input type="text" name="components[0][key]" placeholder="Key">
                    <input type="text" name="components[0][value]" placeholder="Value">
                    <button type="button" onclick="removeComponent(this)">✕</button>
                </div>
            </div>
    
            <button type="button" onclick="addComponent()">+ Tambah Komponen</button>
    
            <hr>
    
            <div class="flex gap-4">
                <a href="{{ route('assets.view.index') }}">Batal</a>
                <button type="submit">Simpan Asset</button>
            </div>
        </form>
    </div>
    
    <script>
    let componentIndex = 1;
    const maxComponents = 10;
    
    function addComponent() {
        if (componentIndex >= maxComponents) {
            alert('Maksimal 10 komponen');
            return;
        }
    
        const wrapper = document.getElementById('component-wrapper');
        const row = document.createElement('div');
        row.className = 'component-row flex gap-2';
    
        row.innerHTML = `
            <input type="text" name="components[${componentIndex}][key]" placeholder="Key">
            <input type="text" name="components[${componentIndex}][value]" placeholder="Value">
            <button type="button" onclick="removeComponent(this)">✕</button>
        `;
    
        wrapper.appendChild(row);
        componentIndex++;
    }
    
    function removeComponent(btn) {
        btn.parentElement.remove();
    }
    </script>
    </x-app-layout>
    