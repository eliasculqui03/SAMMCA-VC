<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('phs')->truncate();

        // Start date 30 days ago from now
        $startDate = Carbon::now()->subDays(30);

        // End date is current time
        $endDate = Carbon::now();

        // Generate data for each hour
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Generate random pH value between 6.5 and 8.5
            $phValue = round(mt_rand(65, 85) / 10, 2);

            // Insert the record
            DB::table('phs')->insert([
                'valor' => $phValue,
                'fecha_hora' => $currentDate->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Move to next hour
            $currentDate->addHour();
        }
    }
}
