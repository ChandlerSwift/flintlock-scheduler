<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 8; $i++) {

            $week = new Week;
            $week->name = 'Week' + $i;
            $week->start_date = '';
            $week->save();
        }
        //
    }
}
