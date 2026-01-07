<x-app-layout>
    <h1>Dashboard</h1>
    
    <h2>Summary</h2>
    
    <ul>
        <li>Total Assets: {{ $total_assets }}</li>
        <li>Total Packages: {{ $total_packages }}</li>
        <li>Pending Assets: {{ $pending_assets }}</li>
    
        @if(auth()->user()->isAdmin())
            <li>Pending Packages: {{ $pending_packages }}</li>
        @endif
    </ul>
    
    <hr>
    
    <h2>Quick Actions</h2>
    
    <ul>
        <li><a href="{{ route('assets.view.create') }}">+ Add Asset</a></li>
        <li><a href="{{ route('packages.view.create') }}">+ Add Package</a></li>
    
        @if(auth()->user()->isAdmin())
            <li><a href="{{ route('approval.assets') }}">Asset Approval</a></li>
            <li><a href="{{ route('approval.packages') }}">Package Approval</a></li>
        @endif
    </ul>
    
    <hr>
    
    <h2>Asset Status Overview</h2>
    
    <table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Status</th>
        <th>Total</th>
    </tr>
    
    @foreach($asset_statuses as $row)
    <tr>
        <td>{{ strtoupper($row->status) }}</td>
        <td>{{ $row->total }}</td>
    </tr>
    @endforeach
    </table>
    
    </x-app-layout>
    