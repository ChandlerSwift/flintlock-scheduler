<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Session;

class SessionSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run($week = 27) //week 1 = 27
    {
        DB::table('sessions')->delete();
        foreach (Program::all() as $program) {
            $timeSlots = [];
            if (in_array($program->id, [1,2,3])) {
                foreach([1,2,3,4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->dayOfWeek = $dayOfWeek;
                    $date->setTime(17, 0, 0); // 5PM
                    array_push($timeSlots, $date);

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->dayOfWeek = $dayOfWeek + 1;
                    $date2->setTime(7, 0, 0); // 7AM
                    array_push($timeSlots, $date2);


                }
            } elseif (in_array($program->id, [4,5,6,7])) {
                foreach([1,2,3,4,5] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->dayOfWeek = $dayOfWeek;
                    $date->setTime(13, 0, 0);
                    
                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->dayOfWeek = $dayOfWeek;
                    $date2->setTime(17, 0, 0); // 5PM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
            }//9
            // [startTime1, endTime1, startTime2, endTime2, startTime3, endTime3, ...]
            foreach ($timeSlots as $timeSlot) { // this is a startTime
                $time = new Carbon($timeSlot);
                $time->week = $week;

                $session = new Session();
                $session->start_time = $timeSlot['start_time'];
                $session->end_time = $timeSlot['end_time'];
                $session->program_id = $program->id;
                $session->save();
            }
        }
        
    }
}
