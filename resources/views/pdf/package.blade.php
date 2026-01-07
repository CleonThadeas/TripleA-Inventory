<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Asset Package Audit PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1, h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        td, th { border: 1px solid #000; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h1>Asset Package Audit Report</h1>

<h2>Package Information</h2>
<table>
<tr><th>Name</th><td>{{ $package->name }}</td></tr>
<tr><th>Status</th><td>{{ $package->status }}</td></tr>
<tr><th>Department</th><td>{{ $package->department->name ?? '-' }}</td></tr>
<tr><th>Location</th><td>{{ $package->location->name ?? '-' }}</td></tr>
<tr><th>Assigned To</th><td>{{ $package->employee->name ?? '-' }}</td></tr>
</table>

<h2>Approval</h2>
<table>
<tr><th>Created By</th><td>{{ $package->creator->name ?? '-' }}</td></tr>
<tr><th>Approved By</th><td>{{ $package->approver->name ?? '-' }}</td></tr>
<tr><th>Approved At</th><td>{{ $package->approved_at }}</td></tr>
</table>

<h2>Package Items</h2>

@foreach($package->items as $item)
<table>
<tr>
    <th colspan="2">Item: {{ $item->name }}</th>
</tr>
<tr>
    <th>Category</th>
    <td>{{ $item->category->name ?? '-' }}</td>
</tr>

@if($item->components->count())
<tr>
    <th colspan="2">Components</th>
</tr>
@foreach($item->components as $c)
<tr>
    <td>{{ $c->key }}</td>
    <td>{{ $c->value }}</td>
</tr>
@endforeach
@else
<tr>
    <td colspan="2">No components</td>
</tr>
@endif
</table>
@endforeach

<h2>Activity Log</h2>
<table>
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

<p>
Generated at {{ now() }}
</p>

</body>
</html>
