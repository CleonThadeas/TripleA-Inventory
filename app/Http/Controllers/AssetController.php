<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Category;
use App\Models\Location;
use App\Models\Department;
use App\Models\ActivityLog;
use App\Models\AssetComponent;
use App\Services\AssetCreationService;
use App\Services\ComponentService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Services\ApprovalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class AssetController extends Controller
{
    public function __construct(
        protected AssetCreationService $assetCreationService,
        protected ComponentService $componentService,
        protected ActivityLogService $activityLogService
    ) {
        $this->middleware('auth');
    }

    /*
    |--------------------------------------------------------------------------
    | API – LIST ASSETS (JSON)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return response()->json(
            Asset::with([
                'category',
                'location',
                'department',
                'creator',
                'approver',
            ])->latest()->get()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | API – CREATE ASSET
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);

        $validated = $request->validate([
            'name'           => 'required|string',
            'category_id'    => 'required|exists:categories,id',
            'location_id'    => 'required|exists:locations,id',
            'department_id'  => 'required|exists:departments,id',
            'employee_name'  => 'nullable|string|max:255',
        
            'serial_code' => 'required|string|max:50',

            'purchase_year'  => 'required|digits:4',
            'brand'          => 'required|string',
            'model'          => 'required|string',
        
            'photo'          => 'nullable|image|max:2048',
        
            'components'             => 'nullable|array',
            'components.*.key'       => 'nullable|string',
            'components.*.value'     => 'nullable|string',
        ]);
        
        // Normalisasi serial code (backend safety)
        $validated['serial_code'] = strtoupper($validated['serial_code']);
        

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')
                ->store('asset_photos', 'public');
        }

        // CREATE ASSET
        $asset = $this->assetCreationService
            ->createSingleAsset($validated, Auth::user());

        // CREATE COMPONENTS
        if (!empty($validated['components'])) {
            foreach ($validated['components'] as $component) {
                AssetComponent::create([
                    'parent_type'     => 'asset',
                    'parent_id'       => $asset->id,
                    'component_key'   => $component['key'],
                    'component_value' => $component['value'],
                ]);
            }
        }

    $this->activityLogService->log(
        model: $asset,
        action: 'create',
        before: null,
        after: $asset->fresh()->toArray(),
        request: $request
    );

    return redirect()
        ->route('assets.view.index')
        ->with('success', 'Asset berhasil ditambahkan');
}

    /*
    |--------------------------------------------------------------------------
    | API – SHOW ASSET (JSON)
    |--------------------------------------------------------------------------
    */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);

        return response()->json(
            $asset->load([
                'category',
                'location',
                'department',
                'creator',
                'approver',
                'components',
            ])
        );
    }

    /*
    |--------------------------------------------------------------------------
    | API – UPDATE ASSET
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Asset $asset)
    {
        $this->authorize('update', $asset);
    
        $validated = $request->validate([
            'name' => 'required',
            'employee_name' => 'nullable',
            'brand' => 'required',
            'model' => 'required',
            'photo' => 'nullable|image|max:2048',
            'components' => 'array',
            'new_components' => 'array',
        ]);
    
        DB::transaction(function () use ($asset, $validated, $request) {
    
            // Foto
            if ($request->hasFile('photo')) {
                if ($asset->photo_path) {
                    Storage::disk('public')->delete($asset->photo_path);
                }
                $validated['photo_path'] =
                    $request->file('photo')->store('asset_photos', 'public');
            }
    
            $asset->update($validated);
    
            // Update component lama
            if (!empty($validated['components'])) {
                foreach ($validated['components'] as $id => $comp) {
                    $asset->components()
                        ->where('id', $id)
                        ->update([
                            'component_key' => $comp['key'],
                            'component_value' => $comp['value'],
                        ]);
                }
            }
    
            // Tambah component baru
            if (!empty($validated['new_components'])) {
                foreach ($validated['new_components'] as $comp) {
                    if (!empty($comp['key']) && !empty($comp['value'])) {
                        $asset->components()->create([
                            'parent_type' => 'asset',
                            'component_key' => $comp['key'],
                            'component_value' => $comp['value'],
                        ]);
                    }
                }
            }
        });
    
        return redirect()
            ->route('assets.view.show', $asset) 
            ->with('success', 'Asset berhasil diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET LIST
    |--------------------------------------------------------------------------
    */
    public function viewIndex(): View
    {
        return view('assets.index', [
            'assets' => Asset::latest()->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET CREATE
    |--------------------------------------------------------------------------
    */
    public function viewCreate(): View
    {
        return view('assets.create', [
            'categories'  => Category::all(),
            'locations'   => Location::all(),
            'departments' => Department::all(),
            'users'       => User::all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET EDIT
    |--------------------------------------------------------------------------
    */
    public function viewEdit(Asset $asset): View
    {
        /** @var User $user */
        $user = Auth::user();

        if (! $user->isAdmin() && $asset->status !== 'pending') {
            abort(403, 'Asset sudah disetujui dan tidak bisa diedit');
        }

        return view('assets.edit', [
            'asset' => $asset,
            'users' => User::all(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET DETAIL
    |--------------------------------------------------------------------------
    */
    public function viewShow(Asset $asset): View
    {
        return view('assets.show', [
            'asset' => $asset->load([
                'category',
                'location',
                'department',
                'components',
            ]),
            'activities' => $asset->activities()->latest()->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET HISTORY
    |--------------------------------------------------------------------------
    */
    public function viewHistory(Asset $asset): View
    {
        $this->authorize('view', $asset);

        return view('assets.history', [
            'asset' => $asset,
            'activities' => $asset->activities()->latest()->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET AUDIT
    |--------------------------------------------------------------------------
    */
    public function auditView(Asset $asset): View
    {
        return view('assets.audit', [
            'asset' => $asset->load([
                'category',
                'location',
                'department',
                'creator',
                'approver',
                'components',
                'activities' => fn ($q) => $q->latest(),
            ]),
        ]);
    }

    public function destroy(Asset $asset)
    {
        $this->authorize('delete', $asset);
    
        DB::transaction(function () use ($asset) {
    
            // Hapus foto
            if ($asset->photo_path) {
                Storage::disk('public')->delete($asset->photo_path);
            }
    
            // Hapus komponen SAJA
            $asset->components()->delete();
    
            // Log history sebelum delete
            app(ActivityLogService::class)->log(
                model: $asset,
                action: 'delete',
                before: $asset->toArray(),
                after: null,
                request: request()
            );
    
            // Hapus asset
            $asset->delete();
        });
    
        return redirect()
            ->route('assets.view.index')
            ->with('success', 'Asset berhasil dihapus (history tetap disimpan)');
    }
    
    
}
