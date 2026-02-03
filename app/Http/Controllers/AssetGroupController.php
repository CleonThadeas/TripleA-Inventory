<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssetGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* =====================================================
     | STORE – CREATE ASSET GROUP
     ===================================================== */
    public function store(Request $request)
    {
        $this->authorize('create', AssetGroup::class);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_groups')
                    ->where(fn ($q) =>
                        $q->where('employee_name', $request->employee_name)
                    ),
            ],
            'employee_name' => 'required|string|max:255',
            'asset_ids'     => 'required|array|min:1',
            'asset_ids.*'   => 'exists:assets,id',
        ]);

        /* =====================================================
         | BUSINESS RULE 1:
         | Hanya asset ACTIVE yang boleh digroup
         ===================================================== */
        $invalidStatus = Asset::whereIn('id', $validated['asset_ids'])
            ->where('status', '!=', 'active')
            ->exists();

        if ($invalidStatus) {
            return back()->withErrors([
                'asset_ids' => 'Hanya asset dengan status ACTIVE yang boleh dimasukkan ke group.',
            ]);
        }

        /* =====================================================
         | BUSINESS RULE 2:
         | 1 Asset hanya boleh berada di 1 Group
         ===================================================== */
        $alreadyGrouped = AssetGroup::whereHas('assets', function ($q) use ($validated) {
            $q->whereIn('assets.id', $validated['asset_ids']);
        })->exists();

        if ($alreadyGrouped) {
            return back()->withErrors([
                'asset_ids' => 'Salah satu asset sudah tergabung dalam group lain.',
            ]);
        }

/** @var \App\Models\User $user */
$user = Auth::user();
$isAdmin = $user->isAdmin();


        DB::transaction(function () use ($validated, $user, $isAdmin) {

            $group = AssetGroup::create([
                'name'            => $validated['name'],
                'employee_name'   => $validated['employee_name'],
                'approval_status' => $isAdmin ? 'approved' : 'pending',
                'approved_by'     => $isAdmin ? $user->id : null,
                'approved_at'     => $isAdmin ? now() : null,
                'created_by'      => $user->id,
            ]);

            $group->assets()->sync($validated['asset_ids']);

            ActivityLogService::log(
                event: 'CREATE',
                model: $group,
                before: null,
                after: $group->load('assets')->toArray(),
                changes: null,
                approvalStatus: 'pending',
                meta: [
                    'context' => 'group_create'
                ]
            );
            
        });

        return redirect()
            ->route('assets.view.index')
            ->with(
                'success',
                $isAdmin
                    ? 'Asset Group berhasil dibuat dan langsung aktif'
                    : 'Asset Group berhasil dibuat dan menunggu approval admin'
            );
    }

    /* =====================================================
     | EDIT – FORM EDIT GROUP
     ===================================================== */public function edit(AssetGroup $asset_group)
{
    $this->authorize('update', $asset_group);

    $currentAssets = $asset_group->assets()->get();

    $availableAssets = Asset::where('status', 'active')
        ->whereDoesntHave('groups')
        ->orWhereHas('groups', fn ($q) =>
            $q->where('asset_groups.id', $asset_group->id)
        )
        ->get();

    return view('asset-groups.edit', [
        'group'           => $asset_group,
        'currentAssets'   => $currentAssets,
        'availableAssets' => $availableAssets,
    ]);
}

    /* =====================================================
     | UPDATE – UPDATE GROUP
     ===================================================== */
    public function update(Request $request, AssetGroup $group)
    {
        $this->authorize('update', $group);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('asset_groups')
                    ->where(fn ($q) =>
                        $q->where('employee_name', $request->employee_name)
                    )
                    ->ignore($group->id),
            ],
            'employee_name' => 'required|string|max:255',
            'asset_ids'     => 'nullable|array',
            'asset_ids.*'   => 'exists:assets,id',
        ]);

/** @var \App\Models\User $user */
$user = Auth::user();
$isAdmin = $user->isAdmin();


        DB::transaction(function () use ($validated, $group, $user, $isAdmin) {

            $before = $group->load('assets')->toArray();

            $group->update([
                'name'          => $validated['name'],
                'employee_name' => $validated['employee_name'],
            ]);

            $assetIds = $validated['asset_ids'] ?? [];

            if (! empty($assetIds)) {
                $group->assets()->sync($assetIds);
            }

            if (! $isAdmin) {
                $group->update([
                    'approval_status' => 'pending',
                    'approved_by'     => null,
                    'approved_at'     => null,
                ]);
            }

            ActivityLogService::log(
                event: 'CREATE',
                model: $group,
                before: null,
                after: $group->load('assets')->toArray(),
                changes: null,
                approvalStatus: 'pending',
                meta: [
                    'context' => 'group_create'
                ]
            );
            
        });

        return redirect()
            ->route('assets.view.index')
            ->with(
                'success',
                $isAdmin
                    ? 'Asset Group berhasil diperbarui'
                    : 'Perubahan group disimpan dan menunggu approval admin'
            );
    }

    /* =====================================================
     | DESTROY – DELETE GROUP
     ===================================================== */
     public function destroy(AssetGroup $asset_group)
     {
         $this->authorize('delete', $asset_group);
     
         DB::transaction(function () use ($asset_group) {
     
             $before = $asset_group->load('assets')->toArray();
     
             $asset_group->assets()->detach();
             $asset_group->delete();
     
             ActivityLogService::log(
                 'DELETE',
                 $asset_group,
                 $before,
                 null,
                 null,
                 'approved',
                 ['context' => 'group_delete']
             );
         });
     
         return redirect()
             ->route('assets.view.index')
             ->with('success', 'Asset Group berhasil dihapus');
     }
     
}
