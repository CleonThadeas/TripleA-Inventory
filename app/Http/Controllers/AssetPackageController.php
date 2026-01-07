<?php

namespace App\Http\Controllers;

use App\Models\AssetPackage;
use App\Models\AssetPackageItem;
use App\Services\ComponentService;
use App\Services\ApprovalService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Component;
use Illuminate\Contracts\View\View;
use App\Models\Department;
use App\Models\Location;

/**
 * 
 * @method $this middleware(array|string $middleware)
 * @method void authorize(string $ability, mixed $arguments = [])
 */
class AssetPackageController extends Controller
{
    public function __construct(
        protected ComponentService $componentService,
        protected ApprovalService $approvalService,
        protected ActivityLogService $activityLogService
    ) {
        $this->middleware('auth');
    }

    /**
     * =========================
     * API: LIST PACKAGES (JSON)
     * =========================
     */
    public function index()
    {
        $packages = AssetPackage::with([
            'items.components',
            'department',
            'location',
            'employee',
            'creator',
            'approver',
        ])->latest()->get();

        return response()->json($packages);
    }

    /**
     * =========================
     * API: CREATE PACKAGE
     * Staff → pending
     * Admin → auto-approved
     * =========================
     */
    public function store(Request $request)
    {
        $this->authorize('create', AssetPackage::class);

        $validated = $request->validate([
            'name'           => 'required|string',
            'department_id'  => 'required|exists:departments,id',
            'location_id'    => 'required|exists:locations,id',
            'employee_id'    => 'nullable|exists:users,id',

            // items
            'items'                  => 'required|array|min:1',
            'items.*.name'           => 'required|string',
            'items.*.category_id'    => 'required|exists:categories,id',

            // item components (optional)
            'items.*.components'     => 'array',
            'items.*.components.*'   => 'string',
        ]);

        $package = DB::transaction(function () use ($validated, $request) {

            $package = new AssetPackage();
            $package->fill($validated);
            $package->created_by = Auth::id();

/** @var User $user */
$user = Auth::user();
            if ($user->isAdmin()) {
                $package->status = 'active';
                $package->approved_by = $user->id;
                $package->approved_at = now();
            } else {
                $package->status = 'pending';
            }

            $package->save();

            foreach ($validated['items'] as $itemData) {
                $item = AssetPackageItem::create([
                    'asset_package_id' => $package->id,
                    'name'             => $itemData['name'],
                    'category_id'      => $itemData['category_id'],
                ]);

                if (!empty($itemData['components'])) {
                    $this->componentService->sync(
                        'package_item',
                        $item->id,
                        $itemData['components']
                    );
                }
            }

            // ACTIVITY LOG (CREATE)
            $this->activityLogService->log(
                $package,
                'create',
                null,
                $package->fresh()->toArray(),
                $request
            );

            return $package;
        });

        return response()->json([
            'message' => 'Asset package created successfully',
            'data'    => $package->load('items.components')
        ], 201);
    }

    /**
     * =========================
     * API: SHOW PACKAGE DETAIL
     * =========================
     */
    public function show(AssetPackage $assetPackage)
    {
        $this->authorize('view', $assetPackage);

        $assetPackage->load([
            'items.components',
            'department',
            'location',
            'employee',
            'creator',
            'approver',
        ]);

        return response()->json($assetPackage);
    }

    /**
     * =========================
     * API: UPDATE PACKAGE
     * Staff → sebelum approve
     * Admin → bebas
     * =========================
     */
    public function update(Request $request, AssetPackage $package)
    {
        $this->authorize('update', $package);
    
        $validated = $request->validate([
            'name'          => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'location_id'   => 'required|exists:locations,id',
            'employee_id'   => 'nullable|exists:users,id',
        ]);
    
        $before = $package->fresh()->toArray();
    
        $package->update($validated);
    
        app(ActivityLogService::class)->log(
            $package,
            'update',
            $before,
            $package->fresh()->toArray(),
            $request
        );
    
        return redirect()
            ->route('packages.view.show', $package->id)
            ->with('success', 'Package updated');
    }
    
    /**
     * =========================
     * VIEW: PACKAGE LIST (HTML)
     * =========================
     */
    public function viewIndex()
    {
        return view('packages.index', [
            'packages' => AssetPackage::latest()->get()
        ]);
    }

    /**
     * =========================
     * VIEW: PACKAGE DETAIL (HTML)
     * =========================
     */
    public function viewShow(AssetPackage $package)
    {
        $this->authorize('view', $package);
    
        $package->load([
            'department',
            'location',
            'employee',
            'creator',
            'approver',
            'items.category',
            'items.components',
            'activities',
        ]);
    
        return view('packages.show', [
            'package' => $package
        ]);
    }
    

    public function viewEdit(AssetPackage $package): View
    {
        /** @var User $user */
        $user = Auth::user();
    
        if (
            ! $user->isAdmin()
            && $package->status !== 'pending'
        ) {
            abort(403, 'Package sudah disetujui dan tidak bisa diedit');
        }
    
        return view('packages.edit', [
            'package' => $package,
            'departments' => Department::all(),
            'locations' => Location::all(),
            'users' => User::all(),
        ]);
    }
    /**
     * =========================
     * VIEW: PACKAGE AUDIT (HTML)
     * =========================
     */
    public function auditView(AssetPackage $package)
    {
        $package->load([
            'department',
            'location',
            'employee',
            'creator',
            'approver',
            'items.category',
            'items.components',
            'activities.user' => function ($q) {
                $q->latest();
            }
        ]);
    
        return view('packages.audit', [
            'package' => $package
        ]);
    }
    
}
