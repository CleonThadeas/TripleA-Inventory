<x-app-layout>
    <h1>Export Activity Log</h1>
    
    <form method="GET" action="{{ route('export.activity') }}">
    
    <h3>Filter Activity</h3>
    
    <p>
    Object Type<br>
    <select name="subject_type">
        <option value="">All</option>
        <option value="asset">Asset</option>
        <option value="package">Asset Package</option>
    </select>
    </p>
    
    <p>
    Action<br>
    <select name="action">
        <option value="">All</option>
        <option value="create">Create</option>
        <option value="update">Update</option>
        <option value="approve">Approve</option>
        <option value="reject">Reject</option>
        <option value="status_change">Status Change</option>
    </select>
    </p>
    
    <p>
    Performed By (User)<br>
    <select name="user_id">
        <option value="">All</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select>
    </p>
    
    <p>
    Date Range<br>
    From <input type="date" name="date_from">
    To <input type="date" name="date_to">
    </p>
    
    <hr>
    
    <h3>Export Format</h3>
    
    <p>
    <select name="format" required>
        <option value="xlsx">Excel (.xlsx)</option>
        <option value="csv">CSV (.csv)</option>
    </select>
    </p>
    
    <button type="submit">Export Activity Log</button>
    
    </form>
    </x-app-layout>
    