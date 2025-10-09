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




    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/device-token', function (Request $request) {
            $data = $request->validate([
                'token' => 'required|string',
                'device_type' => 'nullable|string'
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user->addDeviceToken($data['token'], $data['device_type'] ?? null);

            return response()->json(['success' => true, 'message' => 'Device token registered']);
        });

        Route::delete('/device-token', function (Request $request) {
            $data = $request->validate([
                'token' => 'required|string'
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $user->removeDeviceToken($data['token']);

            return response()->json(['success' => true, 'message' => 'Device token removed']);
        });
    });


