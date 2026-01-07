<h2>Activity Log</h2>

<table>
    <thead>
        <tr>
            <th>Time</th>
            <th>Actor</th>
            <th>Role</th>
            <th>Action</th>
            <th>Before</th>
            <th>After</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($activities as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $log->causer->name ?? 'SYSTEM' }}</td>
                <td>{{ strtoupper($log->causer_role ?? '-') }}</td>
                <td>{{ strtoupper($log->action) }}</td>
                <td><pre>{{ json_encode($log->before, JSON_PRETTY_PRINT) }}</pre></td>
                <td><pre>{{ json_encode($log->after, JSON_PRETTY_PRINT) }}</pre></td>
            </tr>
        @endforeach
    </tbody>
</table>
