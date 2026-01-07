<?php

namespace App\Http\Controllers;

use App\Services\ExportService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Location;
use App\Models\Department;



/**
 * @method $this middleware(array|string $middleware)
 * @method void authorize(string $ability, mixed $arguments = [])
 */

class ExportController extends Controller
{
    public function __construct(
        protected ExportService $exportService
    ) {
        $this->middleware('auth');
    }

    public function exportAssets(Request $request)
    {
        $filters = $request->only([
            'status',
            'category_id',
            'location_id',
            'department_id',
            'year_from',
            'year_to',
        ]);

        $format = $request->get('format', 'xlsx');

        return $this->exportService
            ->exportAssets($filters, $format);
    }
    public function viewForm()
{
    return view('export.assets', [
        'categories'  => Category::all(),
        'locations'   => Location::all(),
        'departments' => Department::all(),
    ]);
}
}
