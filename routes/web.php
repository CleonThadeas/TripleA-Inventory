<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetPackageController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| MASTER DATA CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\DepartmentController;

/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/login'));

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ASSET API (BACKEND)
    |--------------------------------------------------------------------------
    */
    Route::get('/assets', [AssetController::class, 'index']);
    Route::post('/assets', [AssetController::class, 'store'])
    ->name('assets.store');
    Route::get('/assets/{asset}', [AssetController::class, 'show']);
    Route::put('/assets/{asset}', [AssetController::class, 'update'])
    ->name('assets.update');
    Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])
    ->name('assets.destroy');


    /*
    |--------------------------------------------------------------------------
    | ASSET QR CODE
    |--------------------------------------------------------------------------
    */
    Route::post('/assets/{asset}/approve', [ApprovalController::class, 'approve'])
    ->name('assets.approve');

    Route::post('/assets/{asset}/qr',
        [QrCodeController::class, 'generate']
    )->name('assets.qr');
    
    /*
    |--------------------------------------------------------------------------
    | ASSET UI
    |--------------------------------------------------------------------------
    */
    Route::get('/assets-view', [AssetController::class, 'viewIndex'])
        ->name('assets.view.index');

    Route::get('/assets-view/create', [AssetController::class, 'viewCreate'])
        ->name('assets.view.create');

        Route::get('/assets-view/{asset:asset_code}', [AssetController::class, 'viewShow'])
        ->name('assets.view.show');   

    Route::get('/assets-view/{asset}/edit', [AssetController::class, 'viewEdit'])
        ->name('assets.view.edit');

    Route::get('/assets-view/{asset}/history', [AssetController::class, 'viewHistory'])
        ->name('assets.view.history');
// routes/web.php
Route::get('/scan-qr', [QrCodeController::class, 'scanView'])
    ->name('assets.qr.scan');

    /*
    |--------------------------------------------------------------------------
    | ASSET PACKAGE UI
    |--------------------------------------------------------------------------
    */
    Route::get('/packages-view', [AssetPackageController::class, 'viewIndex'])
        ->name('packages.view.index');

    Route::get('/packages-view/create', [AssetPackageController::class, 'viewCreate'])
        ->name('packages.view.create');

    Route::get('/packages-view/{package}', [AssetPackageController::class, 'viewShow'])
        ->name('packages.view.show');

    Route::get('/packages-view/{package}/edit', [AssetPackageController::class, 'viewEdit'])
        ->name('packages.view.edit');

    /*
    |--------------------------------------------------------------------------
    | COMPONENT CRUD (PACKAGE ITEM)
    |--------------------------------------------------------------------------
    */
    Route::post('/components', [ComponentController::class, 'store'])->name('components.store');
    Route::put('/components/{component}', [ComponentController::class, 'update'])->name('components.update');
    Route::delete('/components/{component}', [ComponentController::class, 'destroy'])->name('components.destroy');
    
    // web.php
Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])
->name('assets.destroy');

});

/*
|--------------------------------------------------------------------------
| ADMIN ONLY ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {


    Route::get('/activity-view/recent', [ActivityLogController::class, 'recent'])
    ->name('activity.recent');

    /*
    |--------------------------------------------------------------------------
    | MASTER DATA MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::prefix('master')->group(function () {

   // CATEGORY
   Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
   Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
   Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

   // LOCATION
   Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
   Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
   Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

   // DEPARTMENT
   Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
   Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
   Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

    });

    /*
    |--------------------------------------------------------------------------
    | APPROVAL UI
    |--------------------------------------------------------------------------
    */
    Route::get('/approval/assets', [ApprovalController::class, 'assetIndex'])
        ->name('approval.assets');

    Route::post('/assets/{asset}/approve', [ApprovalController::class, 'approve'])
        ->name('assets.approve');

    Route::post('/assets/{asset}/reject', [ApprovalController::class, 'reject'])
        ->name('assets.reject');

    Route::get('/approval/packages', [ApprovalController::class, 'packageIndex'])
        ->name('approval.packages');

    /*
    |--------------------------------------------------------------------------
    | ACTIVITY LOG UI
    |--------------------------------------------------------------------------
    */
    // VIEW
    Route::get('/export/assets', [ExportController::class, 'viewAssetForm'])
        ->name('export.asset.view');

    Route::get('/export/activity', [ExportController::class, 'viewActivityForm'])
        ->name('export.activity.view');

    // ACTION
    Route::get('/export/assets/run', [ExportController::class, 'exportAssets'])
        ->name('export.asset');

    Route::get('/export/activity/run', [ExportController::class, 'exportActivity'])
        ->name('export.activity');

    /*
    |--------------------------------------------------------------------------
    | AUDIT
    |--------------------------------------------------------------------------
    */
    Route::get('/audit/assets/{asset}', [AssetController::class, 'auditView'])
        ->name('assets.audit.view');

    Route::get('/audit/packages/{package}', [AssetPackageController::class, 'auditView'])
        ->name('packages.audit.view');

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/users-view', [UserManagementController::class, 'index'])
        ->name('users.view.index');

    Route::get('/users-view/{user}/edit', [UserManagementController::class, 'edit'])
        ->name('users.view.edit');

    Route::put('/users-view/{user}', [UserManagementController::class, 'update'])
        ->name('users.update');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (BREEZE)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
