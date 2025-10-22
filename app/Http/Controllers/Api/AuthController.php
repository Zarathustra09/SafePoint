<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'remember' => 'nullable|boolean',
            'fcm_token' => 'nullable|string',
            'device_type' => 'nullable|string|in:android,ios,web',
            'timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        \Log::info('Login attempt', ['email' => $credentials['email']]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if (! $user->is_verified) {
                \Log::warning('Unverified user login attempt', ['email' => $credentials['email']]);
                Auth::logout();

                return response()->json(['error' => 'Account not verified'], 403);
            }

            $token = $user->createToken('authToken')->plainTextToken;

            if ($request->remember) {
                $user->setRememberToken(\Illuminate\Support\Str::random(60));
                $user->save();
            }

            if ($request->fcm_token) {
                $timestamp = $request->timestamp ? Carbon::parse($request->timestamp) : now();
                $user->addDeviceToken(
                    $request->fcm_token,
                    $request->device_type,
                    $timestamp
                );
                \Log::info('FCM token registered on login', [
                    'user_id' => $user->id,
                    'device_type' => $request->device_type,
                ]);
            }

            \Log::info('Successful login', ['user_id' => $user->id]);

            return response()->json([
                'token' => $token,
                'user_id' => $user->id,
                'user' => $user,
                'remember_token' => $request->remember ? $user->getRememberToken() : null,
            ], 200);
        }

        \Log::warning('Unauthorized login attempt', ['email' => $credentials['email']]);

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'valid_id_image' => 'required|file|mimes:jpeg,png,jpg',
            'address' => 'nullable|string|max:1000',
            'fcm_token' => 'nullable|string',
            'device_type' => 'nullable|string|in:android,ios,web',
            'timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $path = $request->file('valid_id_image')->store('valid_ids', 'public');

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
            'valid_id_image' => $path,
            'address' => $request->address,
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        if ($request->fcm_token) {
            $timestamp = $request->timestamp ? Carbon::parse($request->timestamp) : now();
            $user->addDeviceToken(
                $request->fcm_token,
                $request->device_type,
                $timestamp
            );
            \Log::info('FCM token registered on registration', [
                'user_id' => $user->id,
                'device_type' => $request->device_type,
            ]);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => 'Already logged out'], 200);
        }

        if ($request->fcm_token) {
            $user->removeDeviceToken($request->fcm_token);
        }

        $user->currentAccessToken()->delete();
        $user->setRememberToken(null);
        $user->save();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }

    public function loginWithRememberToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'remember_token' => 'required|string',
            'fcm_token' => 'nullable|string',
            'device_type' => 'nullable|string|in:android,ios,web',
            'timestamp' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = \App\Models\User::where('remember_token', $request->remember_token)->first();

        if (! $user) {
            return response()->json(['error' => 'Invalid remember token'], 401);
        }

        if (! $user->is_verified) {
            return response()->json(['error' => 'Account not verified'], 403);
        }

        $token = $user->createToken('authToken')->plainTextToken;

        if ($request->fcm_token) {
            $timestamp = $request->timestamp ? Carbon::parse($request->timestamp) : now();
            $user->addDeviceToken(
                $request->fcm_token,
                $request->device_type,
                $timestamp
            );
        }

        return response()->json([
            'token' => $token,
            'user_id' => $user->id,
            'user' => $user,
        ], 200);
    }
}
