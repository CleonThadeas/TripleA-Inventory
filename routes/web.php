<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    AssetController,
    ComponentController,
    ApprovalController,
    QrCodeController,
    ExportController,
    ActivityLogController,
    UserManagementController,
    AssetGroupController
};
use App\Http\Controllers\Master\{
    CategoryController,
    LocationController,
    DepartmentController
};

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /* ===== DASHBOARD ===== */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /* ===== ASSET API ===== */
    Route::prefix('assets')->name('assets.')->group(function () {
        Route::get('/', [AssetController::class, 'index']);
        Route::post('/', [AssetController::class, 'store'])->name('store');
        Route::get('/{asset}', [AssetController::class, 'show']);
        Route::put('/{asset}', [AssetController::class, 'update'])->name('update');
        Route::delete('/{asset}', [AssetController::class, 'destroy'])->name('destroy');
        Route::post('/{asset}/qr', [QrCodeController::class, 'generate'])->name('qr');
    });

    /* ===== ASSET VIEW ===== */
    Route::prefix('assets-view')->name('assets.view.')->group(function () {
        Route::get('/', [AssetController::class, 'viewIndex'])->name('index');
        Route::get('/create', [AssetController::class, 'viewCreate'])->name('create');
        Route::get('/{asset:asset_code}', [AssetController::class, 'viewShow'])->name('show');
        Route::get('/{asset}/edit', [AssetController::class, 'viewEdit'])->name('edit');
        Route::get('/{asset}/history', [AssetController::class, 'viewHistory'])->name('history');
    });

    /* ===== ASSET GROUP ===== */
    Route::resource('asset-groups', AssetGroupController::class)
        ->parameters(['asset-groups' => 'asset_group'])
        ->only(['store', 'edit', 'update', 'destroy']);

    /* ===== COMPONENT ===== */
    Route::prefix('components')->name('components.')->group(function () {
        Route::post('/', [ComponentController::class, 'store'])->name('store');
        Route::put('/{component}', [ComponentController::class, 'update'])->name('update');
        Route::delete('/{component}', [ComponentController::class, 'destroy'])->name('destroy');
    });

    /* ===== QR SCAN ===== */
    Route::get('/scan-qr', [QrCodeController::class, 'scanView'])
        ->name('assets.qr.scan');
});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {

    /* ===== ACTIVITY LOG ===== */
    Route::get('/activity-view/recent', [ActivityLogController::class, 'recent'])
        ->name('activity.recent');

    /* ===== MASTER DATA ===== */
    Route::prefix('master')->group(function () {
        Route::resource('categories', CategoryController::class)->only(['index','store','destroy']);
        Route::resource('locations', LocationController::class)->only(['index','store','destroy']);
        Route::resource('departments', DepartmentController::class)->only(['index','store','destroy']);
    });

    /* ===== APPROVAL SYSTEM ===== */
    Route::prefix('approvals')->name('approvals.')->group(function () {

        Route::get('/', [ApprovalController::class, 'index'])->name('index');

        // Asset approval
        Route::get('/assets/{asset}', [ApprovalController::class, 'showAsset'])->name('assets.show');
        Route::post('/assets/{asset}/approve', [ApprovalController::class, 'approveAsset'])->name('assets.approve');
        Route::post('/assets/{asset}/reject', [ApprovalController::class, 'rejectAsset'])->name('assets.reject');

        // Group approval
        Route::get('/groups/{asset_group}', [ApprovalController::class, 'showGroup'])->name('groups.show');
        Route::post('/groups/{asset_group}/approve', [ApprovalController::class, 'approveGroup'])->name('groups.approve');
        Route::post('/groups/{asset_group}/reject', [ApprovalController::class, 'rejectGroup'])->name('groups.reject');

        // Change approval
        Route::get('/changes', [ApprovalController::class, 'pendingChanges'])->name('changes');
        Route::get('/changes/{log}', [ApprovalController::class, 'showChange'])->name('changes.show');
        Route::post('/changes/{log}/approve', [ApprovalController::class, 'approveChange'])->name('changes.approve');
        Route::post('/changes/{log}/reject', [ApprovalController::class, 'rejectChange'])->name('changes.reject');
    });

    /* ===== EXPORT ===== */
    Route::prefix('export')->group(function () {
        Route::get('/assets', [ExportController::class, 'viewAssetForm'])->name('export.asset.view');
        Route::get('/activity', [ExportController::class, 'viewActivityForm'])->name('export.activity.view');
        Route::get('/assets/run', [ExportController::class, 'exportAssets'])->name('export.asset');
        Route::get('/activity/run', [ExportController::class, 'exportActivity'])->name('export.activity');
    });

    /* ===== AUDIT ===== */
    Route::get('/audit/assets/{asset}', [AssetController::class, 'auditView'])
        ->name('assets.audit.view');

    /* ===== USER MANAGEMENT ===== */
    Route::prefix('users-view')->name('users.view.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::get('/create', [UserManagementController::class, 'create'])->name('create');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    });
    
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
