<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\WebApi\AnnouncementController;
use App\Http\Controllers\WebApi\CrimeReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/reports/list', [CrimeReportController::class, 'list'])->name('reports.list');
Route::resource('/reports', CrimeReportController::class);

Route::put('/announcements/{announcement}/image/{imageId}', [AnnouncementController::class, 'updateImage']);
Route::delete('/announcements/{announcement}/image/{imageId}', [AnnouncementController::class, 'deleteImage']);
Route::resource('/announcements', AnnouncementController::class);


// Fix the routes order to prioritize named routes before parameter routes
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile/upload-image', [App\Http\Controllers\ProfileController::class, 'uploadImage'])->name('profile.uploadImage');
Route::delete('/profile/reset-image', [App\Http\Controllers\ProfileController::class, 'resetImage'])->name('profile.resetImage')->middleware('auth');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');


Route::prefix('approval')->middleware(['auth'])->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('approval.index');
    Route::get('/{user}', [ApprovalController::class, 'show'])->name('approval.show');
    Route::post('/{user}/approve', [ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/{user}/reject', [ApprovalController::class, 'reject'])->name('approval.reject');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/upload-image', [App\Http\Controllers\Api\ProfileController::class, 'uploadImage']);
    Route::delete('/profile/reset-image', [App\Http\Controllers\Api\ProfileController::class, 'resetImage']);
    Route::delete('/profile', [App\Http\Controllers\Api\ProfileController::class, 'destroy']);
});
