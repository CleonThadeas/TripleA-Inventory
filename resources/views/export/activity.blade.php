<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6">

        <h1 class="text-2xl font-semibold mb-6">
            Export Activity Log
        </h1>

        <form method="GET"
              action="{{ route('export.activity') }}"
              class="bg-white border rounded-lg p-6 space-y-6">

            {{-- ================= FILTER ================= --}}
            <h3 class="font-semibold text-lg">Filter Aktivitas</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- OBJECT TYPE --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Jenis Object
                    </label>
                    <select name="subject_type"
                            class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        <option value="asset">Asset</option>
                        <option value="package">Asset Package</option>
                    </select>
                </div>

                {{-- ACTION --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Aksi
                    </label>
                    <select name="action"
                            class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        <option value="CREATE">Create</option>
                        <option value="UPDATE">Update</option>
                        <option value="APPROVE">Approve</option>
                        <option value="REJECT">Reject</option>
                        <option value="DELETE">Delete</option>
                        <option value="STATUS_CHANGE">Status Change</option>
                    </select>
                </div>

                {{-- USER --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Dilakukan Oleh
                    </label>
                    <select name="user_id"
                            class="w-full border rounded px-3 py-2">
                        <option value="">Semua</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- DATE FROM --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Tanggal Dari
                    </label>
                    <input type="date"
                           name="date_from"
                           class="w-full border rounded px-3 py-2">
                </div>

                {{-- DATE TO --}}
                <div>
                    <label class="block text-sm font-medium mb-1">
                        Tanggal Sampai
                    </label>
                    <input type="date"
                           name="date_to"
                           class="w-full border rounded px-3 py-2">
                </div>

            </div>

            <hr>

            {{-- ================= FORMAT ================= --}}
            <h3 class="font-semibold text-lg">Format Export</h3>

            <div>
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
                        class="px-6 py-2 border rounded font-semibold">
                    Export Activity
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
