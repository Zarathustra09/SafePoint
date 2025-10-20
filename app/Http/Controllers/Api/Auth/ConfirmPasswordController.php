<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ConfirmPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Confirm the user's password (API).
     */
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();

        if (! Hash::check($request->input('password'), $user->password)) {
            return response()->json(['message' => 'Password does not match.'], 403);
        }

        return response()->json(['message' => 'Password confirmed.'], 200);
    }
}
