<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\CentersController;
use App\Http\Controllers\RequestTypesController;
use App\Http\Controllers\RequestStatusesController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\PublicRequestsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Public Routes for Requests System
Route::get('/submit-request', [PublicRequestsController::class, 'create'])->name('public.requests.create');
Route::post('/submit-request', [PublicRequestsController::class, 'store'])->name('public.requests.store');
Route::get('/request-success/{tracking_number}', [PublicRequestsController::class, 'success'])->name('public.requests.success');
Route::get('/track-request', [PublicRequestsController::class, 'trackForm'])->name('public.requests.track');
Route::post('/track-request', [PublicRequestsController::class, 'track'])->name('public.requests.track-result');
Route::get('/download-response/{tracking_number}', [PublicRequestsController::class, 'downloadResponse'])->name('public.requests.download-response');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Centers Management
    Route::middleware(['permission:create_center|edit_center|delete_center'])->group(function () {
        Route::resource('centers', CentersController::class);
    });
    
    // Request Types Management
    Route::middleware(['permission:create_request_type|edit_request_type|delete_request_type'])->group(function () {
        Route::resource('request-types', RequestTypesController::class);
    });
    
    // Request Statuses Management
    Route::middleware(['permission:create_request_status|edit_request_status|delete_request_status'])->group(function () {
        Route::resource('request-statuses', RequestStatusesController::class);
    });
    
    // Requests Management
    Route::middleware(['permission:create_request|edit_request|delete_request'])->group(function () {
        Route::resource('requests', RequestsController::class)->parameters(['requests' => 'req']);
    });
    
    // Activity Log Route (accessible to all authenticated users)
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::delete('/activity-log/delete-all', [ActivityLogController::class, 'deleteAll'])->name('activity-log.delete-all')->middleware('role:super_admin');
    
    // Users Routes (super_admin only)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::resource('users', UsersController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('permissions', PermissionsController::class);
    });

    // Backup Routes (super_admin only)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/backups', [BackupController::class, 'index'])->name('backups.index');
        Route::post('/backups', [BackupController::class, 'create'])->name('backups.create');
        Route::get('/backups/download', [BackupController::class, 'download'])->name('backups.download');
        Route::delete('/backups', [BackupController::class, 'destroy'])->name('backups.destroy');
        Route::get('/backups/stats', [BackupController::class, 'stats'])->name('backups.stats');
    });
});

require __DIR__.'/auth.php';
