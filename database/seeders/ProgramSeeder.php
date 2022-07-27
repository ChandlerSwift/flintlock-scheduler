<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('programs')->insert([
            [
                'name' => 'Huck Finn Overnight',
                'max_participants' => 8,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'Treehouse Overnight',
                'max_participants' => 12,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'Fishing Outpost Overnight',
                'max_participants' => 10,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'Adventure Cove',
                'max_participants' => 12,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ], /* [
                'name'=> 'International Target Sports Outpost',
                'max_participants'=> 12,
                'min_scout_age'=> 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ], */ [
                'name' => 'Five Stand',
                'max_participants' => 6,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'Bike Trek',
                'max_participants' => 10,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'ATV Training Course',
                'max_participants' => 6,
                'min_scout_age' => 14,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],  [
                'name' => 'Waterski',
                'max_participants' => 6,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ], [
                'name'=> 'Mountain Bike Outpost',
                'max_participants'=> 10,
                'min_scout_age'=> 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ], [
                'name' => 'Older Scout Adventure Blast',
                'max_participants' => 12,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ], [
                'name' => 'Water Sports Outpost',
                'max_participants' => 24,
                'min_scout_age' => 13,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
            ],
        ]);
    }
}
