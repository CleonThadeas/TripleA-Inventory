<x-app-layout>
    <h1>Dashboard</h1>
    
    <h2>Summary</h2>
    
    <ul>
        <li>Total Assets: {{ $total_assets }}</li>
    </ul>
    
    <hr>
    
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
    