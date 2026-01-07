<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Asset Audit PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #000; padding: 5px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h1>Asset Audit Report</h1>

<h2>Asset Information</h2>
<table>
<tr><th>Asset Code</th><td>{{ $asset->asset_code }}</td></tr>
<tr><th>Name</th><td>{{ $asset->name }}</td></tr>
<tr><th>Category</th><td>{{ $asset->category->name ?? '-' }}</td></tr>
<tr><th>Location</th><td>{{ $asset->location->name ?? '-' }}</td></tr>
<tr><th>Department</th><td>{{ $asset->department->name ?? '-' }}</td></tr>
<tr><th>Status</th><td>{{ $asset->status }}</td></tr>
</table>

<h2>Procurement</h2>
<table>
<tr><th>Purchase Year</th><td>{{ $asset->purchase_year }}</td></tr>
<tr><th>Brand</th><td>{{ $asset->brand }}</td></tr>
<tr><th>Model</th><td>{{ $asset->model }}</td></tr>
</table>

<h2>Approval</h2>
<table>
<tr><th>Created By</th><td>{{ $asset->creator->name ?? '-' }}</td></tr>
<tr><th>Approved By</th><td>{{ $asset->approver->name ?? '-' }}</td></tr>
<tr><th>Approved At</th><td>{{ $asset->approved_at }}</td></tr>
</table>

@if($asset->components->count())
<h2>Components</h2>
<table>
<tr><th>Key</th><th>Value</th></tr>
@foreach($asset->components as $c)
<tr>
    <td>{{ $c->key }}</td>
    <td>{{ $c->value }}</td>
</tr>
@endforeach
</table>
@endif

<h2>Activity Log</h2>
<table>
<tr>
    <th>Date</th>
    <th>Action</th>
    <th>User</th>
    <th>IP</th>
</tr>
@foreach($asset->activities as $log)
<tr>
    <td>{{ $log->created_at }}</td>
    <td>{{ $log->action }}</td>
    <td>{{ $log->user->name ?? '-' }}</td>
    <td>{{ $log->ip_address }}</td>
</tr>
@endforeach
</table>

<p style="margin-top:20px;">
Generated at {{ now() }}
</p>

</body>
</html>
