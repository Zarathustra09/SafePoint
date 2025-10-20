<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Override the default response after a successful password reset.
     * If the user has the 'Admin' role, keep the usual redirect.
     * Otherwise show the passwordUpdated view.
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $user = auth()->user();

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
            return redirect($this->redirectPath())->with('status', trans($response));
        }

        // Non-admin users: show a simple "password updated" page.
        return response()->view('passwordUpdated', ['status' => trans($response)]);
    }
}
