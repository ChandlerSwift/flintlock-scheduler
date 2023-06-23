<?php

namespace Database\Seeders;

use App\Models\DefaultSession;
use App\Models\Program;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($week = 27) //week 1 = 27
    {
        DB::table('default_sessions')->delete();
        foreach (Program::all() as $program) {
            if (in_array($program->name, ['Huck Finn Overnight', 'Treehouse Overnight'])) {
                foreach ([1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $session = new DefaultSession();
                    $session->start_seconds = $dayOfWeek * 86400 + 17.5 * 3600;
                    $session->end_seconds = ($dayOfWeek + 1) * 86400 + 7 * 3600;
                    $session->program_id = $program->id;
                    $session->save();
                }
            } elseif (in_array($program->name, ['Fishing Outpost Overnight'])) {
                foreach ([1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $session = new DefaultSession();
                    $session->start_seconds = $dayOfWeek * 86400 + 15.5 * 3600;
                    $session->end_seconds = $dayOfWeek * 86400 + 21.5 * 3600;
                    $session->program_id = $program->id;
                    $session->save();
                }
            } elseif (in_array($program->name, ['Adventure Cove', 'Bike Trek', 'ATV Training Course'])) {
                foreach ([1, 2, 3, 4, 5] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $session = new DefaultSession();
                    $session->start_seconds = $dayOfWeek * 86400 + 13 * 3600;
                    $session->end_seconds = $dayOfWeek * 86400 + 17.5 * 3600;
                    $session->program_id = $program->id;
                    $session->save();
                }
            } elseif (in_array($program->name, ['Five Stand', 'Waterski'])) {
                foreach ([1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $session = new DefaultSession();
                    $session->start_seconds = $dayOfWeek * 86400 + 19 * 3600;
                    $session->end_seconds = $dayOfWeek * 86400 + 21 * 3600;
                    $session->program_id = $program->id;
                    $session->save();
                }
            } elseif (in_array($program->name, ['Mountain Bike Outpost', 'Older Scout Adventure Blast', 'Water Sports Outpost'])) {
                $session = new DefaultSession();
                $session->start_seconds = 9 * 3600;
                $session->end_seconds = 11 * 3600;
                $session->program_id = $program->id;
                $session->every_day = true;
                $session->save();
            } else {
                throw new \Exception("Unknown program $program->name");
            }
        }
    }
}
