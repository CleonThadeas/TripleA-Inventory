<x-app-layout>
    <h1>Package Detail</h1>
    
    <p>
    <strong>Name:</strong> {{ $package->name }}<br>
    <strong>Status:</strong> {{ $package->status }}<br>
    <strong>Department:</strong> {{ optional($package->department)->name }}<br>
    <strong>Location:</strong> {{ optional($package->location)->name }}<br>
    <strong>Assigned User:</strong> {{ optional($package->employee)->name ?? '-' }}<br>
    <strong>Created By:</strong> {{ optional($package->creator)->name ?? 'System' }}<br>
    <strong>Approved By:</strong> {{ optional($package->approver)->name ?? '-' }}
    </p>
    
    <hr>
    
    <h2>Package Items</h2>
    
    @if($package->items->isEmpty())
        <p>No items in this package.</p>
    @else
    <table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Item Name</th>
        <th>Category</th>
        <th>Components</th>
    </tr>
    
    @foreach($package->items as $item)
    <tr>
        <td>{{ $item->name }}</td>
        <td>{{ optional($item->category)->name }}</td>
        <td>
            @if($item->components->isEmpty())
                -
            @else
                <ul>
                    @foreach($item->components as $comp)
                        <li>
                            {{ $comp->key }} : {{ $comp->value }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </td>
    </tr>
    @endforeach
    </table>
    @endif
    
    <ul>
        @foreach($item->components as $comp)
        <li>
            {{ $comp->key }} : {{ $comp->value }}
        
            @if(auth()->user()->isAdmin() || $package->status === 'pending')
                <form method="POST" action="{{ route('components.destroy', $comp->id) }}" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            @endif
        </li>
        @endforeach
        </ul>
        
        @if(auth()->user()->isAdmin() || $package->status === 'pending')
        <form method="POST" action="{{ route('components.store') }}">
        @csrf
        <input type="hidden" name="componentable_type" value="App\Models\AssetPackageItem">
        <input type="hidden" name="componentable_id" value="{{ $item->id }}">
        
        <input type="text" name="key" placeholder="Key">
        <input type="text" name="value" placeholder="Value">
        <button type="submit">Add</button>
        </form>
        @endif
        
    <hr>
    
    <h2>Activity History</h2>
    
    @if($package->activities->isEmpty())
        <p>No activity recorded.</p>
    @else
    <ul>
    @foreach($package->activities as $log)
        <li>
            {{ $log->created_at }} —
            <strong>{{ strtoupper($log->action) }}</strong>
            by {{ optional($log->user)->name ?? 'System' }}
        </li>
    @endforeach
    </ul>
    @endif
    
    @if(auth()->user()->isAdmin())
<a href="{{ route('packages.audit.view', $package) }}">
    View Audit
</a>
@endif

    <hr>
    
    <a href="{{ route('packages.view.index') }}">← Back to Package List</a>
    
    @if(auth()->user()->isAdmin() || $package->status === 'pending')
        | <a href="{{ route('packages.view.edit', $package->id) }}">Edit Package</a>
    @endif
    
    
    </x-app-layout>
    