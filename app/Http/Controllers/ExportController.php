<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Location;
use App\Models\Department;
use App\Models\User;

class ExportController extends Controller
{
    public function __construct(
        protected ExportService $exportService
    ) {
        $this->middleware('auth');
    }

    /* =========================
     | VIEW FORM
     ========================= */

    public function viewAssetForm()
    {
        return view('export.asset', [
            'categories'  => Category::all(),
            'locations'   => Location::all(),
            'departments' => Department::all(),
        ]);
    }

    public function viewActivityForm()
    {
        return view('export.activity', [
            'users' => User::all(),
        ]);
    }

    /* =========================
     | EXPORT ACTION
     ========================= */

    public function exportAssets(Request $request)
    {
        $filters = $request->validate([
            'status'        => 'nullable|string',
            'category_id'   => 'nullable|integer',
            'location_id'   => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'year_from'     => 'nullable|integer',
            'year_to'       => 'nullable|integer',
        ]);

        $format = $request->get('format', 'xlsx');

        return $this->exportService
            ->exportAssets($filters, $format);
    }

    public function exportActivity(Request $request)
    {
        $filters = $request->only([
            'subject_type',
            'action',
            'user_id',
            'date_from',
            'date_to',
        ]);
    
        $format = $request->get('format', 'xlsx');
    
        return $this->exportService
            ->exportActivities($filters, $format);
    }
    
}
