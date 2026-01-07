<x-app-layout>
    <h1>Edit Package</h1>
    
    <p>
    <strong>Status:</strong> {{ $package->status }}
    </p>
    
    <form method="POST" action="{{ url('/packages/' . $package->id) }}">
    @csrf
    @method('PUT')
    
    <p>
    Package Name<br>
    <input type="text" name="name" value="{{ $package->name }}" required>
    </p>
    
    <p>
    Department<br>
    <select name="department_id" required>
    @foreach($departments as $d)
        <option value="{{ $d->id }}"
            {{ $package->department_id == $d->id ? 'selected' : '' }}>
            {{ $d->name }}
        </option>
    @endforeach
    </select>
    </p>
    
    <p>
    Location<br>
    <select name="location_id" required>
    @foreach($locations as $l)
        <option value="{{ $l->id }}"
            {{ $package->location_id == $l->id ? 'selected' : '' }}>
            {{ $l->name }}
        </option>
    @endforeach
    </select>
    </p>
    
    <p>
    Assigned User<br>
    <select name="employee_id">
        <option value="">-- None --</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}"
                {{ $package->employee_id == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
            </option>
        @endforeach
    </select>
    </p>
    
    <button type="submit">Update Package</button>
    <a href="{{ route('packages.view.show', $package->id) }}">Cancel</a>
    </form>
    </x-app-layout>
    