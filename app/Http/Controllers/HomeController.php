<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\CrimeReport;
use App\Models\FailedLogin;
use App\Models\LoginAttempt;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $statusCounts = CrimeReport::selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $announcementsCount = Announcement::whereBetween('created_at', [now()->subDays(7), now()])->count();

        // Get crime statistics over the last 30 days
        $crimesByDay = CrimeReport::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get crime statistics by type/severity over the last 30 days
        $crimesBySeverity = CrimeReport::selectRaw('severity, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('severity')
            ->pluck('count', 'severity')
            ->toArray();

        // Get failed login attempts for the current user's email
        $failedLogins = FailedLogin::where('email', auth()->user()->email)
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get recent login attempts for all users (for admin view)
        $loginAttempts = collect();
        if (class_exists(LoginAttempt::class)) {
            $loginAttempts = LoginAttempt::orderBy('created_at', 'desc')
                ->take(20)
                ->get();
        }

        return view('home', [
            'statusCounts' => $statusCounts,
            'announcementsCount' => $announcementsCount,
            'crimesByDay' => $crimesByDay,
            'crimesBySeverity' => $crimesBySeverity,
            'failedLogins' => $failedLogins,
            'loginAttempts' => $loginAttempts,
        ]);
    }
}
