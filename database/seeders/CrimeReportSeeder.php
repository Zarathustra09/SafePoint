<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CrimeReport;
use Carbon\Carbon;

class CrimeReportSeeder extends Seeder
{
   public function run(): void
   {
       $reports = [
           [
               'title' => 'Robbery',
               'description' => 'A robbery occurred near Tanauan Public Market.',
               'severity' => 'high',
               'latitude' => 14.0865,
               'longitude' => 121.1496,
               'address' => 'Tanauan Public Market, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(2),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Physical Assault',
               'description' => 'Reported physical assault at Barangay Darasa.',
               'severity' => 'medium',
               'latitude' => 14.0782,
               'longitude' => 121.1439,
               'address' => 'Barangay Darasa, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(5),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Theft',
               'description' => 'Cellphone theft near Tanauan City Hall.',
               'severity' => 'medium',
               'latitude' => 14.0869,
               'longitude' => 121.1492,
               'address' => 'Tanauan City Hall, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(7),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Vandalism',
               'description' => 'Vandalism reported at Tanauan Plaza.',
               'severity' => 'low',
               'latitude' => 14.0862,
               'longitude' => 121.1498,
               'address' => 'Tanauan Plaza, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(3),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Snatching',
               'description' => 'Bag snatching incident at Barangay Sambat.',
               'severity' => 'medium',
               'latitude' => 14.0910,
               'longitude' => 121.1530,
               'address' => 'Barangay Sambat, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(1),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Burglary',
               'description' => 'Burglary at a residence in Barangay Balele.',
               'severity' => 'high',
               'latitude' => 14.0725,
               'longitude' => 121.1375,
               'address' => 'Barangay Balele, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(10),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Arson',
               'description' => 'Fire incident suspected as arson at Barangay Wawa.',
               'severity' => 'high',
               'latitude' => 14.0801,
               'longitude' => 121.1402,
               'address' => 'Barangay Wawa, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(4),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Pickpocketing',
               'description' => 'Pickpocketing at Tanauan Bus Terminal.',
               'severity' => 'medium',
               'latitude' => 14.0840,
               'longitude' => 121.1510,
               'address' => 'Tanauan Bus Terminal, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(6),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Illegal Drugs',
               'description' => 'Illegal drug activity reported at Barangay Janopol.',
               'severity' => 'high',
               'latitude' => 14.0755,
               'longitude' => 121.1455,
               'address' => 'Barangay Janopol, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(8),
               'reported_by' => 1,
               'report_image' => null,
           ],
           [
               'title' => 'Public Disturbance',
               'description' => 'Public disturbance at Tanauan Plaza during festival.',
               'severity' => 'low',
               'latitude' => 14.0863,
               'longitude' => 121.1497,
               'address' => 'Tanauan Plaza, Tanauan, Batangas',
               'status' => 'resolved',
               'incident_date' => Carbon::now()->subDays(9),
               'reported_by' => 1,
               'report_image' => null,
           ],
       ];

       foreach ($reports as $report) {
           CrimeReport::create($report);
       }
   }
}
