<x-app-layout>
    <h1>Asset Audit Detail</h1>

    <p>
        Halaman ini menampilkan seluruh informasi aset secara lengkap
        beserta jejak aktivitas (audit trail).
    </p>

    <hr>

    <h2>Informasi Dasar</h2>
    <table cellpadding="6">
        <tr>
            <td><b>Asset Code</b></td>
            <td>: {{ $asset->asset_code }}</td>
        </tr>
        <tr>
            <td><b>Nama Asset</b></td>
            <td>: {{ $asset->name }}</td>
        </tr>
        <tr>
            <td><b>Kategori</b></td>
            <td>: {{ $asset->category->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Lokasi</b></td>
            <td>: {{ $asset->location->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Departemen</b></td>
            <td>: {{ $asset->department->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Status</b></td>
            <td>: {{ strtoupper($asset->status) }}</td>
        </tr>
    </table>

    <hr>

    <h2>Informasi Pengguna</h2>
    <table cellpadding="6">
        <tr>
            <td><b>Digunakan Oleh</b></td>
            <td>: {{ $asset->employee_name ?? 'Belum ditentukan' }}</td>
        </tr>
    </table>

    <hr>

    <h2>Informasi Pembelian</h2>
    <table cellpadding="6">
        <tr>
            <td><b>Tahun Pembelian</b></td>
            <td>: {{ $asset->purchase_year }}</td>
        </tr>
        <tr>
            <td><b>Brand</b></td>
            <td>: {{ $asset->brand }}</td>
        </tr>
        <tr>
            <td><b>Model</b></td>
            <td>: {{ $asset->model }}</td>
        </tr>
    </table>

    <hr>

    <h2>Persetujuan</h2>
    <table cellpadding="6">
        <tr>
            <td><b>Dibuat Oleh</b></td>
            <td>: {{ $asset->creator->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Disetujui Oleh</b></td>
            <td>: {{ $asset->approver->name ?? '-' }}</td>
        </tr>
        <tr>
            <td><b>Tanggal Persetujuan</b></td>
            <td>: {{ $asset->approved_at ?? '-' }}</td>
        </tr>
    </table>

    <hr>

    <h2>Komponen Asset</h2>
    @if($asset->components->isEmpty())
        <p><em>Tidak ada komponen terdaftar.</em></p>
    @else
        <ul>
            @foreach($asset->components as $comp)
                <li>{{ $comp->component_key }} : {{ $comp->component_value }}</li>
            @endforeach
        </ul>
    @endif

    <hr>

    <h2>Audit Trail</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Waktu</th>
            <th>Aksi</th>
            <th>User</th>
            <th>IP Address</th>
        </tr>
        @foreach($asset->activities as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ strtoupper($log->action) }}</td>
                <td>{{ $log->user->name ?? 'System' }}</td>
                <td>{{ $log->ip_address ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    <hr>

    <a href="{{ route('assets.view.show', $asset->id) }}">← Kembali ke Detail Asset</a>
</x-app-layout>
