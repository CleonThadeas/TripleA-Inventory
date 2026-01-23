<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6">

        <h1 class="text-2xl font-semibold mb-6">
            Export Data Asset
        </h1>

        <form method="GET"
              action="{{ route('export.asset') }}"
              class="bg-white border rounded-lg p-6 space-y-6">

            {{-- ================= FILTER DATA ================= --}}
            <h3 class="font-semibold text-lg">Filter Asset</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        <option value="pending">Pending</option>
                        <option value="active">Active</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Kategori</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Lokasi</label>
                    <select name="location_id" class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        @foreach($locations as $l)
                            <option value="{{ $l->id }}">{{ $l->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Departemen</label>
                    <select name="department_id" class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Tahun Pembelian Dari</label>
                    <input type="number"
                           name="year_from"
                           class="w-full border rounded px-3 py-2"
                           placeholder="2022">
                </div>

                <div>
                    <label class="block text-sm font-medium">Tahun Pembelian Sampai</label>
                    <input type="number"
                           name="year_to"
                           class="w-full border rounded px-3 py-2"
                           placeholder="2026">
                </div>

            </div>

            <hr>

            {{-- ================= FORMAT ================= --}}
            <h3 class="font-semibold text-lg">Format Export</h3>

            <div>
                <select name="format" class="border rounded px-3 py-2" required>
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                </select>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('assets.view.index') }}"
                   class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 border rounded font-semibold">
                    Export Asset
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
