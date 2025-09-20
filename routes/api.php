<?php

use App\Http\Controllers\Api\AIController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SavedRouteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->get('/my-reports', [App\Http\Controllers\Api\CrimeReportController::class, 'myReports']);
Route::middleware('auth:sanctum')->apiResource('crime-reports', App\Http\Controllers\Api\CrimeReportController::class);
Route::middleware('auth:sanctum')->apiResource('announcements', App\Http\Controllers\Api\AnnouncementController::class);



Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);


Route::post('/safer-route', [AIController::class, 'generateSaferRoute']);
Route::post('/test-google-maps', [AIController::class, 'testGoogleMaps']);
Route::post('/test-gemini', [AIController::class, 'testGemini']);
Route::get('/test-both-apis', [AIController::class, 'testBothAPIs']);
Route::post('/safer-route-debug', [AIController::class, 'generateSaferRouteDebug']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/profile/upload-image', [App\Http\Controllers\Api\ProfileController::class, 'uploadImage']);
    Route::delete('/profile/reset-image', [App\Http\Controllers\Api\ProfileController::class, 'resetImage']);
    Route::delete('/profile', [App\Http\Controllers\Api\ProfileController::class, 'destroy']);


    Route::apiResource('saved-routes', SavedRouteController::class);
    Route::get('saved-routes-stats', [SavedRouteController::class, 'stats']);
});

