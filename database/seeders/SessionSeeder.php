<?php

namespace Database\Seeders;

use App\Models\Program;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            //Overnights
            if (in_array($program->id, [1, 2])) {
                foreach ([0, 1, 2, 3] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(17, 30, 0); // 5PM

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek) + 1;
                    $date2->setTime(7, 0, 0); // 7AM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
                // Fishing
            } elseif (in_array($program->id, [3])) {
                foreach ([0, 1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(15, 30, 0);

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(21, 30, 0);
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
                //Afternoon
            } elseif (in_array($program->id, [4, 5, 7, 8])) {
                foreach ([0, 1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(13, 0, 0);

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(17, 30, 1); // 5PM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
                //MORNING PROGRAMS
                /* }elseif (in_array($program->id, [10, 11, 12])) {
                    foreach([0] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                        $date = new Carbon; // same as ::now()
                        $date->week = $week;
                        $date->day = (Carbon::FRIDAY + $dayOfWeek);
                        $date->setTime(9, 0, 0);

                        $date2 = new Carbon; // same as ::now()
                        $date2->week = $week;
                        $date2->day = (Carbon::FRIDAY + $dayOfWeek);
                        $date2->setTime(11, 30, 0); // 11:30AM
                        array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                    }
                //Bikes
                }elseif (in_array($program->id, [7])) {
                    foreach([0,1,2,3,4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                        $date = new Carbon; // same as ::now()
                        $date->week = $week;
                        $date->day = (Carbon::FRIDAY + $dayOfWeek);
                        $date->setTime(13, 0, 0);

                        $date2 = new Carbon; // same as ::now()
                        $date2->week = $week;
                        $date2->day = (Carbon::FRIDAY + $dayOfWeek);
                        $date2->setTime(21, 0, 0); // 9PM
                        array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                    }*/
            }
            //Evening Program
            elseif (in_array($program->id, [9, 6])) {
                foreach ([0, 1, 2, 3, 4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(19, 0, 0); //7PM

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(21, 0, 0); // 9PM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
            }

            // [startTime1, endTime1, startTime2, endTime2, startTime3, endTime3, ...]
            foreach ($timeSlots as $timeSlot) { // this is a startTime
                // $time = new Carbon($timeSlot);
                // $time->week = $week;

                $session = new Session();
                $session->start_time = $timeSlot['start_time'];
                $session->end_time = $timeSlot['end_time'];
                $session->program_id = $program->id;
                $session->save();
            }
        }
    }
}
