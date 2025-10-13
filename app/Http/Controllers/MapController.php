<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CrimeReport;

class MapController extends Controller
{
    public function index()
    {
        $crimeReports = CrimeReport::with('reporter')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('incident_date', 'desc')
            ->get();

        return view('map.index', compact('crimeReports'));
    }
}
