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
use App\Services\QrCodeService;
use App\Models\AssetGroup;
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

    
   /* =====================================================
     | STORE ASSET
     ===================================================== */
    public function store(Request $request)
    {
        $this->authorize('create', Asset::class);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|exists:categories,id',
            'location_id'    => 'required|exists:locations,id',
            'department_id'  => 'required|exists:departments,id',
            'employee_name'  => 'nullable|string|max:255',

            'serial_code'    => 'required|string|max:100|unique:assets,serial_code',
            'purchase_year'  => 'required|digits:4',
            'brand'          => 'required|string|max:255',
            'model'          => 'required|string|max:255',
            'photo'          => 'nullable|image|max:2048',

            'components' => 'nullable|array',
            'components.*.key'   => 'nullable|string|max:255',
            'components.*.value' => 'nullable|string|max:255',

            'components_description' => 'nullable|string',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $isAdmin = $user->isAdmin();

        $category = Category::findOrFail($validated['category_id']);
        $location = Location::findOrFail($validated['location_id']);

        $assetCode = strtoupper(
            "{$category->code}-{$location->code}-{$validated['serial_code']}-{$validated['purchase_year']}"
        );

        DB::beginTransaction();

        try {
            /* ================= FOTO ================= */
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')
                    ->store('asset_photos', 'public');
            }

            /* ================= ASSET ================= */
            $asset = Asset::create([
                'asset_code'      => $assetCode,
                'serial_code'     => $validated['serial_code'],
                'name'            => $validated['name'],
                'category_id'     => $validated['category_id'],
                'location_id'     => $validated['location_id'],
                'department_id'   => $validated['department_id'],
                'employee_name'   => $validated['employee_name'],
                'purchase_year'   => $validated['purchase_year'],
                'brand'           => $validated['brand'],
                'model'           => $validated['model'],
                'photo_path'      => $photoPath,

                'status'          => $isAdmin ? 'active' : 'pending',
                'approval_status' => $isAdmin ? 'approved' : 'pending',
                'approved_by'     => $isAdmin ? $user->id : null,
                'approved_at'     => $isAdmin ? now() : null,
                'created_by'      => $user->id,
            ]);

            /* ================= COMPONENT MANUAL ================= */
            if (!empty($validated['components'])) {
                foreach ($validated['components'] as $component) {
                    if (!empty($component['key']) && !empty($component['value'])) {
                        $asset->components()->create([
                            'parent_type'     => 'asset',
                            'component_key'   => trim($component['key']),
                            'component_value' => trim($component['value']),
                        ]);
                    }
                }
            }

            /* ================= COMPONENT DESKRIPSI ================= */
            if (!empty($validated['components_description'])) {
                foreach (preg_split("/\r\n|\n|\r/", $validated['components_description']) as $line) {
                    if (str_contains($line, ':')) {
                        [$key, $value] = array_map('trim', explode(':', $line, 2));
                        if ($key && $value) {
                            $asset->components()->create([
                                'parent_type'     => 'asset',
                                'component_key'   => $key,
                                'component_value' => $value,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withErrors(['error' => 'Gagal menyimpan asset: ' . $e->getMessage()])
                ->withInput();
        }

        /* ================= ACTIVITY LOG ================= */
        ActivityLogService::log(
            event: 'CREATE',
            model: $asset,
            before: null,
            after: $asset->toArray(),
            changes: null,
            approvalStatus: $isAdmin ? 'approved' : 'pending',
            meta: [
                'source' => 'asset_create',
                'role'   => $user->role,
            ]
        );
        

        /* ================= REDIRECT ================= */
        return redirect()
            ->route('assets.view.index')
            ->with('success', 'Asset berhasil ditambahkan');
    }

    /* =====================================================
     | VIEW INDEX
     ===================================================== */

    /*
    |--------------------------------------------------------------------------
    | API – SHOW ASSET (JSON)
    |--------------------------------------------------------------------------
    */
    public function show(Asset $asset)
    {
        $this->authorize('view', $asset);
    
        $asset->load([
            'category',
            'location',
            'department',
            'creator',
            'approver',
            'components',
            'activities.user',
        ]);
    
        return view('assets.show', compact('asset'));
    }
    
    
    public function showJson(Asset $asset)
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
    /* =====================================================
     | UPDATE ASSET
     ===================================================== */
     public function update(Request $request, Asset $asset)
     {
         $this->authorize('update', $asset);
 
/** @var \App\Models\User $user */
$user = Auth::user();
$isAdmin = $user->isAdmin();

         $before = $asset->load('components')->toArray();
 
         $validated = $request->validate([
             'name'          => 'required|string|max:255',
             'employee_name' => 'nullable|string|max:255',
             'brand'         => 'required|string|max:255',
             'model'         => 'required|string|max:255',
             'photo' => 'nullable|image|max:2048',

 
             'components'             => 'nullable|array',
             'components.*.key'       => 'required_with:components|string',
             'components.*.value'     => 'required_with:components|string',
 
             'new_components'         => 'nullable|array',
             'new_components.*.key'   => 'required_with:new_components|string',
             'new_components.*.value' => 'required_with:new_components|string',
         ]);
 
         DB::transaction(function () use ($asset, $validated, $request, $user, $isAdmin, $before) {
 
             if ($request->hasFile('photo')) {
                 if ($asset->photo_path) {
                     Storage::disk('public')->delete($asset->photo_path);
                 }
 
                 $validated['photo_path'] = $request->file('photo')
                     ->store('asset_photos', 'public');
             }
 
             if (! $isAdmin) {
                 $validated['approval_status'] = 'pending';
                 $validated['approved_by']     = null;
                 $validated['approved_at']     = null;
                 $validated['status']          = 'pending';
             }
 
             $asset->update($validated);
 
             if (!empty($validated['components'])) {
                 foreach ($validated['components'] as $id => $comp) {
                     $asset->components()
                         ->where('id', $id)
                         ->update([
                             'component_key'   => trim($comp['key']),
                             'component_value' => trim($comp['value']),
                         ]);
                 }
             }
 
             if (!empty($validated['new_components'])) {
                 foreach ($validated['new_components'] as $comp) {
                     $asset->components()->create([
                         'parent_type'     => 'asset',
                         'component_key'   => trim($comp['key']),
                         'component_value' => trim($comp['value']),
                     ]);
                 }
             }
 
             $after = $asset->load('components')->toArray();
 
             ActivityLogService::log(
                event: 'UPDATE',
                model: $asset,
                before: $before,
                after: $after,
                changes: null,
                approvalStatus: $isAdmin ? 'approved' : 'pending',
                meta: [
                    'role' => $user->role,
                    'note' => $isAdmin
                        ? 'Updated by admin'
                        : 'Updated by staff, pending approval',
                ]
            );
            
         });
 
         return redirect()
             ->route('assets.view.show', $asset)
             ->with(
                 'success',
                 $isAdmin
                     ? 'Asset berhasil diperbarui'
                     : 'Perubahan disimpan dan menunggu approval admin'
             );
     }
 
    

    /*
    |--------------------------------------------------------------------------
    | VIEW – ASSET LIST
    |--------------------------------------------------------------------------
    */

    public function viewIndex(Request $request)
    {
        $query = Asset::query()->with([
            'category',
            'location',
            'department',
            'groups', // ⬅️ PENTING
        ]);
    
        // SEARCH
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('asset_code', 'like', "%$q%")
                    ->orWhere('name', 'like', "%$q%")
                    ->orWhere('serial_code', 'like', "%$q%");
            });
        }
    
        // FILTER (tetap)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('purchase_year')) {
            $query->where('purchase_year', $request->purchase_year);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        return view('assets.index', [
            'assets'      => $query->latest()->paginate(10)->withQueryString(),
            'groups'      => AssetGroup::with('assets')->latest()->get(), // ⬅️ BARU
            'categories'  => Category::all(),
            'locations'   => Location::all(),
            'departments' => Department::all(),
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
    
            // HAPUS FOTO
            if ($asset->photo_path) {
                Storage::disk('public')->delete($asset->photo_path);
            }
    
            // SNAPSHOT SEBELUM HAPUS
            $before = $asset->load('components')->toArray();
    
            // HAPUS RELASI & ASSET
            $asset->components()->delete();
            $asset->delete();
    
            // LOG DELETE (🔥 FIXED)
            ActivityLogService::log(
                event: 'DELETE',
                model: $asset,
                before: $before,
                after: null,
                changes: null,
                approvalStatus: 'approved',
                meta: [
                    'context' => 'asset_delete'
                ]
            );
        });
    
        return redirect()
            ->route('assets.view.index')
            ->with('success', 'Asset berhasil dihapus');
    }
    
    
    public function approve(Asset $asset)
    {
        $this->authorize('approve', $asset);
    
        $asset->update([
            'approval_status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
    
        return back()->with('success', 'Asset approved');
    }
    
    
    
}
