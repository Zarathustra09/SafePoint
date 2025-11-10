<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\FailedLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected function authenticated($request, $user)
    {
        if (!$user->hasRole('Admin')) {
            auth()->logout();
            return redirect('/login')->withErrors([
                'email' => 'Access denied. Only Admins can log in.',
            ]);
        }

        // If remember me is checked, regenerate the remember token
        if ($request->filled('remember') && $request->input('remember')) {
            $user->setRememberToken(\Illuminate\Support\Str::random(60));
            $user->save();
        }
    }

    /**
     * Record failed login attempts and return the usual failed login response.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Record the failed login attempt
        try {
            FailedLogin::create([
                'email' => $request->input($this->username()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        } catch (\Throwable $e) {
            // Swallow any errors to avoid breaking authentication flow
        }

        // Return the standard failed login response
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin($request)
    {
        // Check if the remember checkbox is checked
        $remember = $request->filled('remember') && $request->input('remember');

        return $this->guard()->attempt(
            $this->credentials($request),
            $remember // Enable "remember me" functionality
        );
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(\Illuminate\Http\Request $request)
    {
        $user = $this->guard()->user();

        // Clear the remember token
        if ($user) {
            $user->setRememberToken(null);
            $user->save();
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect('/login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
