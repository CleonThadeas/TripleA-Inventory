<x-app-layout>
    <h1>Export Assets</h1>
    
    <form method="GET" action="{{ route('export.assets') }}">
    
    <h3>Filters</h3>
    
    <p>
    Asset Type<br>
    <select name="type">
        <option value="">All</option>
        <option value="single">Single Asset</option>
        <option value="package">Asset Package</option>
    </select>
    </p>
    
    <p>
    Status<br>
    <select name="status">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="active">Active</option>
        <option value="maintenance">Maintenance</option>
        <option value="damaged">Damaged</option>
        <option value="lost">Lost</option>
    </select>
    </p>
    
    <p>
    Category<br>
    <select name="category_id">
        <option value="">All</option>
        @foreach($categories as $c)
            <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
    </select>
    </p>
    
    <p>
    Location<br>
    <select name="location_id">
        <option value="">All</option>
        @foreach($locations as $l)
            <option value="{{ $l->id }}">{{ $l->name }}</option>
        @endforeach
    </select>
    </p>
    
    <p>
    Department<br>
    <select name="department_id">
        <option value="">All</option>
        @foreach($departments as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
        @endforeach
    </select>
    </p>
    
    <p>
    Purchase Year Range<br>
    From <input type="number" name="year_from" size="4">
    To <input type="number" name="year_to" size="4">
    </p>
    
    <hr>
    
    <h3>Export Format</h3>
    
    <p>
    <select name="format" required>
        <option value="xlsx">Excel (.xlsx)</option>
        <option value="csv">CSV (.csv)</option>
    </select>
    </p>
    
    <button type="submit">Export</button>
    
    </form>
    </x-app-layout>
    