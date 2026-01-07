<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('master.departments.index', [
            'departments' => Department::orderBy('name')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:departments,name',
        ]);

        Department::create($validated);

        return back()->with('success', 'Department berhasil ditambahkan');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return back()->with('success', 'Department berhasil dihapus');
    }
}
