<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicCalendar;
use Carbon\Carbon;

class AcademicCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['academic_year' => '2025/2026', 'event_name' => 'Tahun Ajaran Baru', 'start_date' => '2025-07-07', 'end_date' => '2025-07-07'],
            ['academic_year' => '2025/2026', 'event_name' => 'Libur Semester 1', 'start_date' => '2025-12-15', 'end_date' => '2026-01-05'],
            ['academic_year' => '2025/2026', 'event_name' => 'UAS Semester 1', 'start_date' => '2025-11-10', 'end_date' => '2025-12-12'],
            ['academic_year' => '2025/2026', 'event_name' => 'UAS Semester 2', 'start_date' => '2026-05-18', 'end_date' => '2026-06-12'],
            ['academic_year' => '2025/2026', 'event_name' => 'Libur Akhir Tahun', 'start_date' => '2026-06-20', 'end_date' => '2026-07-05'],
        ];

        foreach ($events as $event) {
            AcademicCalendar::firstOrCreate($event);
        }
    }
}
