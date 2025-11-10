<?php

namespace App\Http\Controllers\WebApi;

use App\Http\Controllers\Controller;
use App\Models\CrimeReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class CrimeReportController extends Controller
{
    public function index()
    {
        $crimeReports = CrimeReport::with('reporter')->get();

        return view('crime-report.index', compact('crimeReports'));
    }

    public function create()
    {
        return view('crime-report.create');
    }

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
        ]);

        $validated['reported_by'] = Auth::id();

        $crimeReport = CrimeReport::create($validated);

        return redirect()->route('crime-reports.index')
            ->with('success', 'Crime report created successfully.');
    }

    public function show($id)
    {
        $crimeReport = CrimeReport::findOrFail($id);

        return view('crime-report.show', compact('crimeReport'));
    }

    public function edit($id)
    {
        $crimeReport = CrimeReport::findOrFail($id);

        return view('crime-report.edit', compact('crimeReport'));
    }

    public function destroy($id)
    {
        $crimeReport = CrimeReport::findOrFail($id);
        $crimeReport->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Crime report deleted successfully.');
    }

    public function update(Request $request, $id)
    {
        $crimeReport = CrimeReport::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:255',
            'status' => 'required|in:pending,under_investigation,resolved,closed',
            'incident_date' => 'required|date',
        ]);

        $crimeReport->update($validated);

        return redirect()->route('reports.index')
            ->with('success', 'Crime report updated successfully.');
    }

    public function list()
    {
        $crimeReports = CrimeReport::with('reporter')->get();

        return view('crime-report.list', compact('crimeReports'));
    }

    public function export()
    {
        return Excel::download(
            new class implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\ShouldAutoSize, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithMapping
            {
                public function collection()
                {
                    return CrimeReport::with('reporter')->orderBy('incident_date', 'desc')->get();
                }

                public function headings(): array
                {
                    return [
                        'ID',
                        'Title',
                        'Description',
                        'Severity',
                        'Status',
                        'Address',
                        'Incident Date',
                        'Reported By',
                        'Created At',
                    ];
                }

                public function map($report): array
                {
                    return [
                        $report->id,
                        $report->title,
                        $report->description,
                        ucfirst($report->severity),
                        ucfirst(str_replace('_', ' ', $report->status)),
                        $report->address,
                        optional($report->incident_date)->format('Y-m-d H:i:s'),
                        $report->reporter->name ?? 'Unknown',
                        optional($report->created_at)->format('Y-m-d H:i:s'),
                    ];
                }
            },
            'crime-reports-'.now()->format('Y-m-d').'.xlsx'
        );
    }
}
