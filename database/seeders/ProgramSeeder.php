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
            'id'=> 1,
            'name'=> 'Huck Finn Overnight',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 2,
            'name'=> 'Treehouse Overnight',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 3,
            'name'=> 'Fishing Outpost',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 4,
            'name'=> 'Adventure Cove',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 5,
            'name'=> 'I.T.S.O',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 6,
            'name'=> 'Five Stand',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 7,
            'name'=> 'Bike Trek',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 8,
            'name'=> 'Bike Outpost',
            'max_participants'=> 10,
            'min_scout_age'=> 12,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]
        ,  [
            'id'=> 9,
            'name'=> 'ATV Day Ride',
            'max_participants'=> 8,
            'min_scout_age'=> 16,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]
        ]);
    }
}
