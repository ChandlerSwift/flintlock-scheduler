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
            'name'=> 'Huck Finn',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 2,
            'name'=> 'Tree House',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 3,
            'name'=> 'Fishing Overnight',
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
            'name'=> 'International Target Sports Outpost',
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
            'max_participants'=> 10,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]
        ,  [
            'id'=> 8,
            'name'=> 'ATV Safety Training',
            'max_participants'=> 6,
            'min_scout_age'=> 14,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]
         ,  [
            'id'=> 9,
            'name'=> 'Water Skiing',
            'max_participants'=> 6,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ]/*,  [
            'id'=> 11,
            'name'=> 'Older Scout Adventure Blast',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ],  [
            'id'=> 12,
            'name'=> 'Water Sports Outpost',
            'max_participants'=> 12,
            'min_scout_age'=> 13,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now()
        ], */
        ]);
    }
}
