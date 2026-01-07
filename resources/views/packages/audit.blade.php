<x-app-layout>

    <h1>Asset Package Audit Detail</h1>
    
    <hr>
    
    <h3>Package Information</h3>
    <ul>
        <li><b>Name:</b> {{ $package->name }}</li>
        <li><b>Status:</b> {{ $package->status }}</li>
        <li><b>Department:</b> {{ $package->department->name ?? '-' }}</li>
        <li><b>Location:</b> {{ $package->location->name ?? '-' }}</li>
        <li><b>Assigned To:</b> {{ $package->employee->name ?? 'Unassigned' }}</li>
    </ul>
    
    <h3>Approval</h3>
    <ul>
        <li><b>Created By:</b> {{ $package->creator->name ?? '-' }}</li>
        <li><b>Approved By:</b> {{ $package->approver->name ?? '-' }}</li>
        <li><b>Approved At:</b> {{ $package->approved_at ?? '-' }}</li>
    </ul>
    
    <hr>
    
    <h3>Package Items</h3>
    
    @foreach($package->items as $item)
        <h4>Item: {{ $item->name }}</h4>
        <ul>
            <li><b>Category:</b> {{ $item->category->name ?? '-' }}</li>
        </ul>
    
        @if($item->components->count())
        <ul>
            @foreach($item->components as $c)
                <li>{{ $c->key }} : {{ $c->value }}</li>
            @endforeach
        </ul>
        @else
            <p>No components</p>
        @endif
    @endforeach
    
    <hr>
    
    <h3>Activity Log (Audit Trail)</h3>
    
    <table border="1" cellpadding="6">
    <tr>
        <th>Date</th>
        <th>Action</th>
        <th>User</th>
        <th>IP</th>
    </tr>
    
    @foreach($package->activities as $log)
    <tr>
        <td>{{ $log->created_at }}</td>
        <td>{{ $log->action }}</td>
        <td>{{ $log->user->name ?? '-' }}</td>
        <td>{{ $log->ip_address }}</td>
    </tr>
    @endforeach
    </table>
    <a href="{{ route('pdf.package', $package) }}" target="_blank">
        Export Package PDF
    </a>
    
    
    </x-app-layout>
    