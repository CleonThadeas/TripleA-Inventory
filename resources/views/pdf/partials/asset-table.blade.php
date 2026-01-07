<h2>Asset List</h2>

<table>
    <thead>
        <tr>
            <th>Asset Code</th>
            <th>Name</th>
            <th>Category</th>
            <th>Location</th>
            <th>Department</th>
            <th>Employee</th>
            <th>Year</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($assets as $asset)
            <tr>
                <td>{{ $asset->asset_code }}</td>
                <td>{{ $asset->name }}</td>
                <td>{{ $asset->category->name ?? '-' }}</td>
                <td>{{ $asset->location->name ?? '-' }}</td>
                <td>{{ $asset->department->name ?? '-' }}</td>
                <td>{{ $asset->employee->name ?? '-' }}</td>
                <td>{{ $asset->purchase_year }}</td>
                <td>{{ strtoupper($asset->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
