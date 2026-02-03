<x-app-layout>
    <div class="max-w-5xl mx-auto px-6 py-6 space-y-6">

        {{-- ================= HEADER ================= --}}
        <h1 class="text-2xl font-semibold">
            Export Activity Log
        </h1>

        <form method="GET"
              action="{{ route('export.activity') }}"
              class="bg-white border rounded-lg p-6 space-y-8">

            {{-- ================= FILTER ================= --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg">Filter Aktivitas</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- SUBJECT TYPE --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Jenis Object
                        </label>
                        <select name="subject_type"
                                class="w-full border rounded px-3 py-2">
                            <option value="both">Asset & Group</option>
                            <option value="asset">Asset Saja</option>
                            <option value="group">Asset Group Saja</option>
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
                            <option value="CREATE_GROUP">Create Group</option>
                            <option value="UPDATE_GROUP">Update Group</option>
                            <option value="DELETE_GROUP">Delete Group</option>
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
            </div>

            <hr>

            {{-- ================= MODE SHEET ================= --}}
            <div class="space-y-4">
                <h3 class="font-semibold text-lg">Mode Export</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">
                            Mode Sheet
                        </label>
                        <select name="sheet_mode"
                                class="w-full border rounded px-3 py-2"
                                required>
                            <option value="separate">Sheet Terpisah</option>
                            <option value="single">Satu Sheet (Timeline)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">
                            Format File
                        </label>
                        <select name="format"
                                class="w-full border rounded px-3 py-2"
                                required>
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- ================= ACTION ================= --}}
            <div class="flex justify-end gap-4 pt-4">
                <a href="{{ route('assets.view.index') }}"
                   class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded font-semibold">
                    Export Activity Log
                </button>
            </div>

        </form>

    </div>
</x-app-layout>
