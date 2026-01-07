<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-6 space-y-6">
    
        <h1 class="text-2xl font-semibold">Master Department</h1>
    
        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                <b>Gagal:</b>
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    
            <script>
                alert("Nama department sudah ada atau tidak valid.");
            </script>
        @endif
    
        {{-- FORM TAMBAH --}}
        <form method="POST"
              action="{{ route('departments.store') }}"
              class="border p-4 space-y-4">
            @csrf
    
            <h3 class="font-semibold">Tambah Department</h3>
    
            <div>
                <label>Nama Department</label><br>
                <input type="text"
                       name="name"
                       class="border px-3 py-2 w-full"
                       required>
            </div>
    
            <button type="submit"
                    class="px-4 py-2 border bg-gray-100">
                Simpan
            </button>
        </form>
    
        {{-- LIST --}}
        <div class="border p-4">
            <h3 class="font-semibold mb-3">Daftar Department</h3>
    
            <table class="w-full border">
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
    
                @forelse ($departments as $dept)
                <tr>
                    <td class="border px-3 py-2">{{ $dept->name }}</td>
                    <td class="border px-3 py-2 text-center">
                        <form method="POST"
                              action="{{ route('departments.destroy', $dept->id) }}"
                              onsubmit="return confirm('Hapus department ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center py-4 text-gray-500">
                        Belum ada department
                    </td>
                </tr>
                @endforelse
            </table>
        </div>
    
    </div>
    </x-app-layout>
    