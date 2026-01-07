<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return view('master.locations.index', [
            'locations' => Location::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:10|unique:locations,code',
        ]);

        Location::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
        ]);

        return back()->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function destroy(Location $location)
    {
        $location->delete();

        return back()->with('success', 'Lokasi berhasil dihapus');
    }
}
