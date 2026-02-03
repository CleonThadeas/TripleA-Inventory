<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /* =====================================================
     | APPROVAL DASHBOARD
     ===================================================== */
    public function index()
    {
        return view('approvals.index', [

            /* ================= ASSET ================= */
            'pendingAssets' => Schema::hasColumn('assets', 'approval_status')
                ? Asset::where('approval_status', 'pending')
                    ->with(['category', 'creator']) // ✅ ASSET memang punya creator
                    ->latest()
                    ->get()
                : collect(),

            /* ================= GROUP ================= */
            'pendingGroups' => Schema::hasColumn('asset_groups', 'approval_status')
                ? AssetGroup::where('approval_status', 'pending')
                    ->with(['assets']) // ✅ creator DIHAPUS (INI KUNCI FIX)
                    ->latest()
                    ->get()
                : collect(),

            /* ================= CHANGES ================= */
            'pendingChanges' => ActivityLog::where('action', 'UPDATE')
                ->whereJsonContains('meta->note', 'pending')
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }

    /* =====================================================
     | DETAIL ASSET
     ===================================================== */
    public function showAsset(Asset $asset)
    {
        return view('approvals.asset', [
            'asset' => $asset->load([
                'category',
                'location',
                'department',
                'components',
                'creator',
            ]),
        ]);
    }

    /* =====================================================
     | DETAIL GROUP
     ===================================================== */
    public function showGroup(AssetGroup $group)
    {
        return view('approvals.group', [
            'group' => $group->load([
                'assets.components',
            ]),
        ]);
    }

    /* =====================================================
     | APPROVE ASSET
     ===================================================== */
    public function approveAsset(Asset $asset)
    {
        if ($asset->approval_status !== 'pending') {
            return back()->with('error', 'Asset sudah diproses.');
        }

        DB::transaction(function () use ($asset) {
            $asset->update([
                'approval_status' => 'approved',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
                'status'          => 'active',
            ]);
        });

        return back()->with('success', 'Asset berhasil di-approve.');
    }

    /* =====================================================
     | REJECT ASSET
     ===================================================== */
    public function rejectAsset(Asset $asset)
    {
        if ($asset->approval_status !== 'pending') {
            return back()->with('error', 'Asset sudah diproses.');
        }

        $asset->update([
            'approval_status' => 'rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return back()->with('success', 'Asset ditolak.');
    }

    /* =====================================================
     | APPROVE GROUP
     ===================================================== */
    public function approveGroup(AssetGroup $group)
    {
        if ($group->approval_status !== 'pending') {
            return back()->with('error', 'Group sudah diproses.');
        }

        DB::transaction(function () use ($group) {
            $group->update([
                'approval_status' => 'approved',
                'approved_by'     => Auth::id(),
                'approved_at'     => now(),
            ]);

            foreach ($group->assets as $asset) {
                $asset->update(['status' => 'active']);
            }
        });

        return back()->with('success', 'Asset Group berhasil di-approve.');
    }

    /* =====================================================
     | REJECT GROUP
     ===================================================== */
    public function rejectGroup(AssetGroup $group)
    {
        if ($group->approval_status !== 'pending') {
            return back()->with('error', 'Group sudah diproses.');
        }

        $group->update([
            'approval_status' => 'rejected',
            'approved_by'     => Auth::id(),
            'approved_at'     => now(),
        ]);

        return back()->with('success', 'Asset Group ditolak.');
    }

    /* =====================================================
     | PENDING CHANGES LIST
     ===================================================== */
    public function pendingChanges()
    {
        return view('approvals.changes', [
            'logs' => ActivityLog::where('action', 'UPDATE')
                ->whereJsonContains('meta->note', 'pending')
                ->with('user')
                ->latest()
                ->get(),
        ]);
    }

    /* =====================================================
     | SHOW CHANGE DETAIL
     ===================================================== */
    public function showChange(ActivityLog $log)
    {
        return view('approvals.change-detail', compact('log'));
    }

    /* =====================================================
     | APPROVE CHANGE
     ===================================================== */
    public function approveChange(ActivityLog $log)
    {
        return back()->with('success', 'Perubahan disetujui.');
    }

    /* =====================================================
     | REJECT CHANGE
     ===================================================== */
    public function rejectChange(ActivityLog $log)
    {
        return back()->with('success', 'Perubahan ditolak.');
    }
}
