<x-app-layout>
    <div class="max-w-5xl mx-auto p-6 space-y-6">

        <div class="flex justify-between">
            <h1 class="text-xl font-semibold">User Management</h1>

            <a href="{{ route('users.view.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">
                + Tambah User
            </a>
        </div>

        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2">Nama</th>
                    <th class="p-2">Email</th>
                    <th class="p-2">Role</th>
                    <th class="p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ strtoupper($user->role) }}</td>

                    <td class="p-2 flex gap-3">
                        {{-- EDIT --}}
                        <a href="{{ route('users.view.edit', $user) }}"
                           class="text-blue-600 text-sm">
                            Edit
                        </a>

                        {{-- DELETE --}}
                        <form method="POST"
                              action="{{ route('users.view.destroy', $user) }}"
                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="text-red-600 text-sm">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</x-app-layout>
