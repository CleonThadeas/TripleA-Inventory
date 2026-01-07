<?php

namespace App\Http\Controllers;

use App\Models\AssetComponent;
use App\Models\Asset;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
        $this->middleware('auth');
    }

    /**
     * STORE COMPONENT (ADD)
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'parent_type'     => 'required|in:asset,package_item',
            'parent_id'       => 'required|integer',
            'component_key'   => 'required|string|max:100',
            'component_value' => 'required|string|max:255',
        ]);

        // Ambil parent (saat ini hanya asset)
        if ($validated['parent_type'] === 'asset') {
            $parent = Asset::findOrFail($validated['parent_id']);
        } else {
            abort(400, 'Invalid parent type');
        }

        // RBAC
        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot modify components on approved asset');
        }

        DB::transaction(function () use ($validated, $parent, $request) {

            $before = $parent->fresh()->toArray();

            AssetComponent::create([
                'parent_type'     => $validated['parent_type'],
                'parent_id'       => $validated['parent_id'],
                'component_key'   => strtoupper(trim($validated['component_key'])),
                'component_value' => trim($validated['component_value']),
            ]);

            $this->activityLogService->log(
                model: $parent,
                action: 'component_add',
                before: $before,
                after: $parent->fresh()->toArray(),
                request: $request
            );
        });

        return back()->with('success', 'Komponen berhasil ditambahkan.');
    }

    /**
     * UPDATE COMPONENT (EDIT)
     */
    public function update(Request $request, AssetComponent $component): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'component_key'   => 'required|string|max:100',
            'component_value' => 'required|string|max:255',
        ]);

        // Parent asset
        if ($component->parent_type !== 'asset') {
            abort(400, 'Invalid component parent');
        }

        $parent = Asset::findOrFail($component->parent_id);

        // RBAC
        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot modify components on approved asset');
        }

        DB::transaction(function () use ($component, $validated, $parent, $request) {

            $before = $parent->fresh()->toArray();

            $component->update([
                'component_key'   => strtoupper(trim($validated['component_key'])),
                'component_value' => trim($validated['component_value']),
            ]);

            $this->activityLogService->log(
                model: $parent,
                action: 'component_update',
                before: $before,
                after: $parent->fresh()->toArray(),
                request: $request
            );
        });

        return back()->with('success', 'Komponen berhasil diperbarui.');
    }

    /**
     * DELETE COMPONENT
     */
    public function destroy(AssetComponent $component): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if ($component->parent_type !== 'asset') {
            abort(400, 'Invalid component parent');
        }

        $parent = Asset::findOrFail($component->parent_id);

        // RBAC
        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot delete components on approved asset');
        }

        DB::transaction(function () use ($component, $parent) {

            $before = $parent->fresh()->toArray();

            $component->delete();

            $this->activityLogService->log(
                model: $parent,
                action: 'component_delete',
                before: $before,
                after: $parent->fresh()->toArray(),
                request: request()
            );
        });

        return back()->with('success', 'Komponen berhasil dihapus.');
    }
}
