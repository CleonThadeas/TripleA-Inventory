<x-app-layout>
    <div class="max-w-xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-2xl font-semibold">
            Tambah User Baru
        </h1>

        <form method="POST"
              action="{{ route('users.view.store') }}"
              class="bg-white border rounded-lg p-6 space-y-5">
            @csrf

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       class="w-full border rounded px-3 py-2"
                       required>
                @error('name')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email') }}"
                       class="w-full border rounded px-3 py-2"
                       required>
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password"
                       name="password"
                       class="w-full border rounded px-3 py-2"
                       required>
                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- CONFIRM PASSWORD --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Konfirmasi Password
                </label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full border rounded px-3 py-2"
                       required>
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>
                <select name="role"
                        class="w-full border rounded px-3 py-2"
                        required>
                    <option value="">-- Pilih Role --</option>
                    <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>
                        Staff
                    </option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>
                </select>
                @error('role')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('users.view.index') }}"
                   class="px-4 py-2 border rounded">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded">
                    Simpan User
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
