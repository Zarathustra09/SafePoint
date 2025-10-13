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

// Nested comment routes for announcements
Route::middleware('auth:sanctum')->apiResource('announcements.comments', App\Http\Controllers\Api\CommentController::class)->shallow();


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
                'device_type' => 'nullable|string|in:android,ios,web',
                'timestamp' => 'nullable|date'
            ]);

            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $timestamp = isset($data['timestamp']) ? \Carbon\Carbon::parse($data['timestamp']) : now();
            $user->addDeviceToken($data['token'], $data['device_type'] ?? null, $timestamp);

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

        Route::post('/device-token/refresh', function (Request $request) {
            $user = $request->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $staleTokensRemoved = $user->removeStaleTokens();

            return response()->json([
                'success' => true,
                'message' => 'Token refresh completed',
                'stale_tokens_removed' => $staleTokensRemoved
            ]);
        });


    });
//// Debug: send a direct test notification to verify delivery end-to-end
//Route::post('/debug/send-test-notification', function (Request $request) {
//    $data = $request->validate([
//        'title' => 'nullable|string',
//        'body' => 'nullable|string',
//    ]);
//
////    $user = $request->user();
////    if (!$user) {
////        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
////    }
//
//    // Fetch all active device tokens
//    $tokens = \App\Models\UserDeviceToken::getAllActiveTokens();
//    if (empty($tokens)) {
//        return response()->json(['success' => false, 'message' => 'No active device tokens found'], 404);
//    }
//
//    $title = $data['title'] ?? 'Test Notification';
//    $body = $data['body'] ?? 'This is a test push to all active tokens.';
//
//    /** @var \Kreait\Firebase\Contract\Messaging $messaging */
//    $messaging = app(\Kreait\Firebase\Contract\Messaging::class);
//
//    $notification = \Kreait\Firebase\Messaging\Notification::create($title, $body);
//
//    $androidConfig = \Kreait\Firebase\Messaging\AndroidConfig::fromArray([
//        'priority' => 'high',
//        'ttl' => '3600s',
//        'notification' => [
//            'sound' => 'default',
//            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//            'tag' => 'debug',
//        ],
//    ]);
//
//    $apnsConfig = \Kreait\Firebase\Messaging\ApnsConfig::fromArray([
//        'headers' => [
//            'apns-push-type' => 'alert',
//            'apns-priority' => '10',
//        ],
//        'payload' => [
//            'aps' => [
//                'sound' => 'default',
//                'content-available' => 1,
//                'mutable-content' => 1,
//                'category' => 'debug',
//            ],
//        ],
//    ]);
//
//    $message = \Kreait\Firebase\Messaging\CloudMessage::new()
//        ->withNotification($notification)
//        ->withAndroidConfig($androidConfig)
//        ->withApnsConfig($apnsConfig)
//        ->withData([
//            'type' => 'debug_broadcast',
//            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
//        ]);
//
//    try {
//        $report = $messaging->sendMulticast($message, $tokens);
//
//        // Touch success tokens
//        $successTokens = array_map(
//            fn ($s) => $s->target()->value(),
//            $report->successes()->getItems()
//        );
//        if (!empty($successTokens)) {
//            \App\Models\UserDeviceToken::touchTokens($successTokens);
//        }
//
//        return response()->json([
//            'success' => true,
//            'successful_sends' => $report->successes()->count(),
//            'failed_sends' => $report->failures()->count(),
//            'total_tokens' => count($tokens),
//        ]);
//    } catch (\Throwable $e) {
//        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
//    }
//});
