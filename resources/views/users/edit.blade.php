<x-app-layout>
    <div class="max-w-xl mx-auto px-6 py-6 space-y-6">

        <h1 class="text-2xl font-semibold">
            Edit User
        </h1>

        @php
            $isSuperAdmin = $user->email === 'admin@inventory.local';
        @endphp

        <form method="POST"
        action="{{ route('users.view.update', $user) }}"
              class="bg-white border rounded-lg p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- NAME --}}
            <div>
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
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
                       value="{{ $user->email }}"
                       class="w-full border rounded px-3 py-2 bg-gray-100"
                       readonly>
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-medium mb-1">Role</label>

                @if($isSuperAdmin)
                    <input type="text"
                           value="Admin (Super Admin)"
                           class="w-full border rounded px-3 py-2 bg-gray-100"
                           readonly>
                @else
                    <select name="role"
                            class="w-full border rounded px-3 py-2"
                            required>
                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                    </select>
                @endif
            </div>

            {{-- PASSWORD (OPTIONAL) --}}
            <div>
                <label class="block text-sm font-medium mb-1">
                    Password Baru (Opsional)
                </label>
                <input type="password"
                       name="password"
                       class="w-full border rounded px-3 py-2"
                       placeholder="Kosongkan jika tidak ingin mengubah">
                @error('password')
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
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</x-app-layout>
