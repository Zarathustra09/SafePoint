<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        // Identify the user by the provided email in the request (auth may be null)
        $email = $request->input('email');
        $user = $email ? User::where('email', $email)->first() : null;

        if ($user && method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
            // Admins: behave as normal (they will be authenticated in resetPassword)
            return redirect($this->redirectPath())->with('status', trans($response));
        }

        // Non-admin users: ensure they are not authenticated and show a "password updated" page.
        // (We do not authenticate non-admins in resetPassword.)
        return response()->view('passwordUpdated', ['status' => trans($response)]);
    }

    /**
     * Override resetPassword to avoid auto-login for non-admin users.
     */
    protected function resetPassword($user, $password)
    {
        // Set the new password and remember token
        $user->password = Hash::make($password);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // Only authenticate if the user is an Admin
        if (method_exists($user, 'hasRole') && $user->hasRole('Admin')) {
            $this->guard()->login($user);
        }
    }
}
