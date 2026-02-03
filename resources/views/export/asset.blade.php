<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <h1 class="text-2xl font-semibold">
            Export Data Asset
        </h1>

        <form method="GET"
              action="{{ route('export.asset') }}"
              class="bg-white border rounded-lg p-6 space-y-8">

            {{-- ================= FILTER DATA ================= --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg">Filter Asset</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- STATUS --}}
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

                    {{-- CATEGORY --}}
                    <div>
                        <label class="block text-sm font-medium">Kategori</label>
                        <select name="category_id" class="w-full border rounded px-3 py-2">
                            <option value="">Semua</option>
                            @foreach($categories as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- LOCATION --}}
                    <div>
                        <label class="block text-sm font-medium">Lokasi</label>
                        <select name="location_id" class="w-full border rounded px-3 py-2">
                            <option value="">Semua</option>
                            @foreach($locations as $l)
                                <option value="{{ $l->id }}">{{ $l->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- DEPARTMENT --}}
                    <div>
                        <label class="block text-sm font-medium">Departemen</label>
                        <select name="department_id" class="w-full border rounded px-3 py-2">
                            <option value="">Semua</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- YEAR FROM --}}
                    <div>
                        <label class="block text-sm font-medium">Tahun Pembelian Dari</label>
                        <input type="number"
                               name="year_from"
                               class="w-full border rounded px-3 py-2"
                               placeholder="2022">
                    </div>

                    {{-- YEAR TO --}}
                    <div>
                        <label class="block text-sm font-medium">Tahun Pembelian Sampai</label>
                        <input type="number"
                               name="year_to"
                               class="w-full border rounded px-3 py-2"
                               placeholder="2026">
                    </div>

                </div>
            </div>

            <hr>

            {{-- ================= SCOPE EXPORT ================= --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg">Jenis Data yang Diexport</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- EXPORT TYPE --}}
                    <div>
                        <label class="block text-sm font-medium">Tipe Asset</label>
                        <select name="export_type"
                                class="w-full border rounded px-3 py-2"
                                required>
                            <option value="single">Single Asset saja</option>
                            <option value="group">Asset Group saja</option>
                            <option value="both">Single Asset + Group</option>
                        </select>
                    </div>

                    {{-- SHEET MODE --}}
                    <div>
                        <label class="block text-sm font-medium">Mode Sheet</label>
                        <select name="sheet_mode"
                                class="w-full border rounded px-3 py-2"
                                required>
                            <option value="separate">Sheet Terpisah</option>
                            <option value="single">Satu Sheet (Urut Waktu)</option>
                        </select>
                    </div>

                </div>
            </div>

            <hr>

            {{-- ================= FORMAT ================= --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg">Format Export</h3>

                <select name="format"
                        class="border rounded px-3 py-2"
                        required>
                    <option value="xlsx">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                </select>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('assets.view.index') }}"
                   class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded font-semibold">
                    Export Data
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
