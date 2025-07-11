<?php

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

