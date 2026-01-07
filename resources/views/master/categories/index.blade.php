<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-2xl font-semibold">Master Category</h1>

        {{-- ===================== ALERT ERROR ===================== --}}
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
                alert("Kode kategori sudah ada atau tidak valid.");
            </script>
        @endif

        {{-- ===================== FORM CREATE ===================== --}}
        <form method="POST"
              action="{{ route('categories.store') }}"
              class="border p-4 space-y-4">
            @csrf

            <h3 class="font-semibold">Tambah Category</h3>

            <div>
                <label>Nama Category</label><br>
                <input type="text"
                       name="name"
                       class="border px-3 py-2 w-full"
                       required>
            </div>

            <div>
                <label>Kode Category (maks. 3)</label><br>
                <input type="text"
                       name="code"
                       maxlength="3"
                       class="border px-3 py-2 w-32"
                       required
                       oninput="this.value = this.value.toUpperCase()">
            </div>

            <button type="submit"
                    class="px-4 py-2 border bg-gray-100">
                Simpan
            </button>
        </form>

        {{-- ===================== LIST ===================== --}}
        <div class="border p-4">
            <h3 class="font-semibold mb-3">Daftar Category</h3>

            <table class="w-full border">
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2">Kode</th>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>

                @forelse ($categories as $cat)
                <tr>
                    <td class="border px-3 py-2 text-center">{{ $cat->code }}</td>
                    <td class="border px-3 py-2">{{ $cat->name }}</td>
                    <td class="border px-3 py-2 text-center">
                        <form method="POST"
                              action="{{ route('categories.destroy', $cat->id) }}"
                              onsubmit="return confirm('Hapus category ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4 text-gray-500">
                        Belum ada category
                    </td>
                </tr>
                @endforelse
            </table>
        </div>

    </div>
</x-app-layout>
