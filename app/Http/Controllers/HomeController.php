<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrimeReport;
use App\Models\Announcement;

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

        return view('home', [
            'statusCounts' => $statusCounts,
            'announcementsCount' => $announcementsCount,
        ]);
    }
}
