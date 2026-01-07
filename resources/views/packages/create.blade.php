<x-app-layout>

    <h1>Buat Asset Package</h1>
    
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li style="color:red">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="/packages">
        @csrf
    
        <hr>
        <h3>Informasi Package</h3>
    
        <p>
            Nama Package<br>
            <input type="text" name="name" value="{{ old('name') }}">
        </p>
    
        <p>
            Department<br>
            <select name="department_id">
                <option value="">-- pilih --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </p>
    
        <p>
            Location<br>
            <select name="location_id">
                <option value="">-- pilih --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </p>
    
        <p>
            Digunakan Oleh (Opsional)<br>
            <select name="employee_id">
                <option value="">-- tidak ada --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </p>
    
        <hr>
        <h3>Item dalam Package</h3>
    
        {{-- ITEM 1 (WAJIB, MINIMAL 1) --}}
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px">
            <h4>Item #1</h4>
    
            <p>
                Nama Item<br>
                <input type="text" name="items[0][name]">
            </p>
    
            <p>
                Kategori<br>
                <select name="items[0][category_id]">
                    <option value="">-- pilih --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </p>
    
            <p>
                Komponen (Opsional)<br>
                <small>format: key = value</small><br>
                <input type="text" name="items[0][components][RAM]" placeholder="RAM">
                <input type="text" name="items[0][components][Storage]" placeholder="Storage">
            </p>
        </div>
    
        {{-- ITEM 2 (CONTOH TAMBAHAN MANUAL) --}}
        <div style="border:1px solid #000; padding:10px; margin-bottom:10px">
            <h4>Item #2</h4>
    
            <p>
                Nama Item<br>
                <input type="text" name="items[1][name]">
            </p>
    
            <p>
                Kategori<br>
                <select name="items[1][category_id]">
                    <option value="">-- pilih --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </p>
    
            <p>
                Komponen (Opsional)<br>
                <input type="text" name="items[1][components][CPU]" placeholder="CPU">
                <input type="text" name="items[1][components][GPU]" placeholder="GPU">
            </p>
        </div>
    
        <hr>
    
        <button type="submit">Simpan Package</button>
        <a href="/packages-view">Batal</a>
    
    </form>
    
    </x-app-layout>
    