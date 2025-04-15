<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConductividadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Clear existing data
        DB::table('conductividads')->truncate();

        // Start date 30 days ago from now
        $startDate = Carbon::now()->subDays(30);

        // End date is current time
        $endDate = Carbon::now();

        // Generate data for each hour
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            // Generate random conductivity value between 250 and 2500
            $conductividadValue = round(mt_rand(250, 2500), 2);

            // Insert the record
            DB::table('conductividads')->insert([
                'valor' => $conductividadValue,
                'fecha_hora' => $currentDate->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Move to next hour
            $currentDate->addHour();
        }
    }
}
