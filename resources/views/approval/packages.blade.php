<x-app-layout>
    <h1>Package Approval</h1>
    
    @if($packages->isEmpty())
        <p>No pending packages.</p>
    @else
    <table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Package Name</th>
        <th>Department</th>
        <th>Location</th>
        <th>Assigned User</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>
    
    @foreach($packages as $pkg)
    <tr>
        <td>
            <a href="{{ route('packages.view.show', $pkg->id) }}">
                {{ $pkg->name }}
            </a>
        </td>
        <td>{{ optional($pkg->department)->name }}</td>
        <td>{{ optional($pkg->location)->name }}</td>
        <td>{{ optional($pkg->employee)->name ?? '-' }}</td>
        <td>{{ optional($pkg->creator)->name ?? 'System' }}</td>
        <td>{{ $pkg->created_at }}</td>
        <td>
            <form method="POST" action="{{ url('/packages/' . $pkg->id . '/approve') }}" style="display:inline">
                @csrf
                <button type="submit">Approve</button>
            </form>
    
            <form method="POST" action="{{ url('/packages/' . $pkg->id . '/reject') }}" style="display:inline">
                @csrf
                <button type="submit">Reject</button>
            </form>
        </td>
    </tr>
    @endforeach
    </table>
    @endif
    </x-app-layout>
    