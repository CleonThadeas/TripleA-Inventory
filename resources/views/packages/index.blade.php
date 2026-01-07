<x-app-layout>
    <h1>Asset Packages</h1>
    
    <a href="{{ route('packages.view.create') }}">+ Tambah Package</a>
    
    <ul>
    @foreach($packages as $package)
        <li>
            <a href="{{ route('packages.view.show', $package->id) }}">
                {{ $package->name }} ({{ $package->status }})
            </a>
        </li>
    @endforeach
    </ul>

<table border="1" cellpadding="5">
    <tr>
        <th>Name</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    @foreach ($packages as $package)
    <tr>
        <td>{{ $package->name }}</td>
        <td>{{ $package->status }}</td>
        <td>
            <a href="/packages-view/{{ $package->id }}">Detail</a>
        </td>
    </tr>
    @if(auth()->user()->isAdmin())
<a href="{{ route('packages.audit.view', $package) }}">
    View Audit
</a>
@endif
    @endforeach
</table>
</x-app-layout>
