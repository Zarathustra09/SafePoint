<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\WebApi\AnnouncementController;
use App\Http\Controllers\WebApi\CrimeReportController;
use App\Http\Controllers\WebApi\RoleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    }

    return view('auth.login');
});

Auth::routes(['register' => false]);
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/reports/list', [CrimeReportController::class, 'list'])->name('reports.list');
    Route::get('/reports/export', [CrimeReportController::class, 'export'])->name('reports.export');
    Route::resource('/reports', CrimeReportController::class);
    Route::put('/announcements/{announcement}/image/{imageId}', [AnnouncementController::class, 'updateImage']);
    Route::delete('/announcements/{announcement}/image/{imageId}', [AnnouncementController::class, 'deleteImage']);
    Route::resource('/announcements', AnnouncementController::class);

    // User management routes
    Route::get('/admin/users', [App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/toggle-blocked', [App\Http\Controllers\UserController::class, 'toggleBlocked'])->name('admin.users.toggle-blocked');
    Route::post('/admin/users/{user}/toggle-restricted', [App\Http\Controllers\UserController::class, 'toggleRestricted'])->name('admin.users.toggle-restricted');
    Route::get('/admin/users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
});

// Fix the routes order to prioritize named routes before parameter routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/upload-image', [App\Http\Controllers\ProfileController::class, 'uploadImage'])->name('profile.uploadImage');
    Route::delete('/profile/reset-image', [App\Http\Controllers\ProfileController::class, 'resetImage'])->name('profile.resetImage');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('approval')->middleware(['auth'])->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/{user}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::post('/{user}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/{user}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('/roles/{user}/assign-admin', [RoleController::class, 'assignAdmin'])->name('roles.assignAdmin');
    Route::post('/roles/{user}/demote-admin', [RoleController::class, 'demoteAdmin'])->name('roles.demoteAdmin');
});

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/map', [MapController::class, 'index'])->name('map.index');
