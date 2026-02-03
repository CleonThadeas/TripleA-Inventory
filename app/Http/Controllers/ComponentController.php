<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetComponent;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComponentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * STORE COMPONENT
     * - Mode MANUAL (key + value)
     * - Mode DESKRIPSI (KEY : VALUE per baris)
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'parent_type'            => 'required|in:asset',
            'parent_id'              => 'required|integer|exists:assets,id',
            'component_key'          => 'nullable|string|max:100',
            'component_value'        => 'nullable|string|max:255',
            'components_description' => 'nullable|string',
        ]);

        $parent = Asset::findOrFail($validated['parent_id']);

        // RBAC
        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot modify components on approved asset');
        }

        DB::transaction(function () use ($validated, $parent, $user) {

            $before = $parent->load('components')->toArray();

            /* ================= MODE 1: DESKRIPSI ================= */
            if (!empty($validated['components_description'])) {
                $lines = preg_split("/\r\n|\n|\r/", $validated['components_description']);

                foreach ($lines as $line) {
                    if (! str_contains($line, ':')) {
                        continue;
                    }

                    [$key, $value] = array_map('trim', explode(':', $line, 2));

                    if ($key === '' || $value === '') {
                        continue;
                    }

                    $parent->components()->create([
                        'parent_type'     => 'asset',
                        'component_key'   => strtoupper($key),
                        'component_value' => $value,
                    ]);
                }
            }

            /* ================= MODE 2: MANUAL ================= */
            if (!empty($validated['component_key']) && !empty($validated['component_value'])) {
                $parent->components()->create([
                    'parent_type'     => 'asset',
                    'component_key'   => strtoupper(trim($validated['component_key'])),
                    'component_value' => trim($validated['component_value']),
                ]);
            }

            ActivityLogService::log(
                'UPDATE',
                $parent,
                $before,
                $parent->load('components')->toArray(),
                null,
                $user->isAdmin() ? 'approved' : 'pending',
                ['context' => 'component_add']
            );
        });

        return back()->with('success', 'Komponen berhasil ditambahkan.');
    }

    /**
     * UPDATE COMPONENT
     */
    public function update(Request $request, AssetComponent $component): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'component_key'   => 'required|string|max:100',
            'component_value' => 'required|string|max:255',
        ]);

        if ($component->parent_type !== 'asset') {
            abort(400, 'Invalid component parent');
        }

        $parent = Asset::findOrFail($component->parent_id);

        // RBAC
        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot modify components on approved asset');
        }

        DB::transaction(function () use ($component, $validated, $parent, $user) {

            $before = $parent->load('components')->toArray();

            $component->update([
                'component_key'   => strtoupper(trim($validated['component_key'])),
                'component_value' => trim($validated['component_value']),
            ]);

            ActivityLogService::log(
                'UPDATE',
                $parent,
                $before,
                $parent->load('components')->toArray(),
                null,
                $user->isAdmin() ? 'approved' : 'pending',
                ['context' => 'component_update']
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

        if (! $user->isAdmin() && $parent->status !== 'pending') {
            abort(403, 'Cannot delete components on approved asset');
        }

        DB::transaction(function () use ($component, $parent, $user) {

            $before = $parent->load('components')->toArray();

            $component->delete();

            ActivityLogService::log(
                'UPDATE',
                $parent,
                $before,
                $parent->load('components')->toArray(),
                null,
                $user->isAdmin() ? 'approved' : 'pending',
                ['context' => 'component_delete']
            );
        });

        return back()->with('success', 'Komponen berhasil dihapus.');
    }
}
