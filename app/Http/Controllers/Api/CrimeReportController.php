<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CrimeReportController extends Controller
{
    public function index(Request $request)
    {
        $query = CrimeReport::with('reporter');

        if ($request->has('severity')) {
            $query->bySeverity($request->severity);
        }

        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        if ($request->has(['lat', 'lng', 'radius'])) {
            $query->nearLocation(
                $request->lat,
                $request->lng,
                $request->radius ?? 10
            );
        }

        $crimeReports = $query->latest()->paginate(15);

        return $crimeReports;
    }


    // app/Http/Controllers/Api/CrimeReportController.php

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'incident_date' => 'required|date',
            'reported_by' => 'nullable|exists:users,id',
            'report_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('report_image')) {
            $validated['report_image'] = $request->file('report_image')->store('report_images', 'public');
        }

        $crimeReport = CrimeReport::create($validated);

        return $crimeReport->load('reporter');
    }

    public function update(Request $request, CrimeReport $crimeReport)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'severity' => 'sometimes|in:low,medium,high,critical',
            'latitude' => 'sometimes|numeric|between:-90,90',
            'longitude' => 'sometimes|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'status' => 'sometimes|in:pending,under_investigation,resolved,closed',
            'incident_date' => 'sometimes|date',
            'report_image' => 'nullable|file|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('report_image')) {
            $validated['report_image'] = $request->file('report_image')->store('report_images', 'public');
        }

        $crimeReport->update($validated);

        return $crimeReport->load('reporter');
    }



    public function show(CrimeReport $crimeReport)
    {
        return $crimeReport->load('reporter');
    }



    public function destroy(CrimeReport $crimeReport)
    {
        $crimeReport->delete();

        return response()->noContent();
    }
}
