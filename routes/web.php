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

Route::resource('/announcements', AnnouncementController::class);
