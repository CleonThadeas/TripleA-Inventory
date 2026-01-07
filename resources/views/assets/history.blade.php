<x-app-layout>
    <h1>Riwayat Asset</h1>

    <p>
        <strong>Asset Code:</strong>
        {{ $asset->asset_code ?? ($activities->first()->before_data['asset_code'] ?? '-') }}
    </p>

    <a href="{{ route('assets.view.index') }}">← Kembali ke daftar asset</a>

    <hr>

    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Waktu</th>
            <th>Aksi</th>
            <th>User</th>
            <th>Detail</th>
        </tr>

        @forelse($activities as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ strtoupper($log->action) }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>
                    {{-- PRIORITAS META MESSAGE --}}
                    @if(isset($log->meta['message']))
                        <em>{{ $log->meta['message'] }}</em>
                    @elseif($log->before_data && $log->after_data)
                        <ul>
                            @foreach($log->after_data as $key => $value)
                                @if(isset($log->before_data[$key]) && $log->before_data[$key] != $value)
                                    <li>
                                        <strong>{{ $key }}</strong>:
                                        "{{ $log->before_data[$key] }}"
                                        →
                                        "{{ $value }}"
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        -
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Tidak ada riwayat.</td>
            </tr>
        @endforelse
    </table>
</x-app-layout>
