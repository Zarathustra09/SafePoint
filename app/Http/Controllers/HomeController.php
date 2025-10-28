<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\CrimeReport;

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

        return view('home', [
            'statusCounts' => $statusCounts,
            'announcementsCount' => $announcementsCount,
            'crimesByDay' => $crimesByDay,
            'crimesBySeverity' => $crimesBySeverity,
        ]);
    }
}
