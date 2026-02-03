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

    /* =========================================================
     | VIEW FORM – ASSET EXPORT
     ========================================================= */
    public function viewAssetForm()
    {
        return view('export.asset', [
            'categories'  => Category::all(),
            'locations'   => Location::all(),
            'departments' => Department::all(),
        ]);
    }

    /* =========================================================
     | VIEW FORM – ACTIVITY LOG EXPORT
     ========================================================= */
    public function viewActivityForm()
    {
        return view('export.activity', [
            'users' => User::all(),
        ]);
    }

    /* =========================================================
     | EXPORT ASSETS (SINGLE / GROUP / BOTH)
     ========================================================= */
    public function exportAssets(Request $request)
    {
        $validated = $request->validate([
            'status'        => 'nullable|string',
            'category_id'   => 'nullable|integer',
            'location_id'   => 'nullable|integer',
            'department_id' => 'nullable|integer',
            'year_from'     => 'nullable|integer',
            'year_to'       => 'nullable|integer',

            'export_type'   => 'required|in:single,group,both',
            'sheet_mode'    => 'required|in:single,separate',
            'format'        => 'required|in:xlsx,csv',
        ]);

        $filters = [
            'status'        => $validated['status']        ?? null,
            'category_id'   => $validated['category_id']   ?? null,
            'location_id'   => $validated['location_id']   ?? null,
            'department_id' => $validated['department_id'] ?? null,
            'year_from'     => $validated['year_from']     ?? null,
            'year_to'       => $validated['year_to']       ?? null,
        ];

        // ⚠️ POSitional arguments — AMAN
        return $this->exportService->exportAssets(
            $filters,
            $validated['export_type'],
            $validated['sheet_mode'],
            $validated['format']
        );
    }

    /* =========================================================
     | EXPORT ACTIVITY LOG
     ========================================================= */
    public function exportActivity(Request $request)
    {
        $validated = $request->validate([
            'subject_type' => 'nullable|in:asset,group,both',
            'action'       => 'nullable|string',
            'user_id'      => 'nullable|integer',
            'date_from'    => 'nullable|date',
            'date_to'      => 'nullable|date',
            'sheet_mode'   => 'required|in:single,separate',
            'format'       => 'required|in:xlsx,csv',
        ]);

        $filters = [
            'subject_type' => $validated['subject_type'] ?? 'both',
            'action'       => $validated['action']       ?? null,
            'user_id'      => $validated['user_id']      ?? null,
            'date_from'    => $validated['date_from']    ?? null,
            'date_to'      => $validated['date_to']      ?? null,
        ];

        return $this->exportService->exportActivities(
            $filters,
            $validated['sheet_mode'],
            $validated['format']
        );
    }
}
