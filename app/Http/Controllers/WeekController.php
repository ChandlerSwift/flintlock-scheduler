<?php

namespace App\Http\Controllers;

use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WeekController extends Controller
{
    public function select(Request $request)
    {
        if (Week::find($request->cookie('week_id'))) {
            return redirect('/');
        }
        return view('select_week')
            ->with('weeks', Week::all());
    }

    public function choose(Week $week)
    {
        return redirect('/')->cookie('week_id', $week->id);
    }

    public function addSessions(Week $week) {
        foreach (\App\Models\Program::all() as $program) {
            $timeSlots = [];
            //Overnights
            if (in_array($program->id, [1,2])) {
                foreach([0,1,2,3] as $dayOfWeek) { // Mon, Tues, Wed, Thurs
                    $date = new Carbon; // same as ::now()
                    $date->week = $week->start_date->week;
                    $date->day = (Carbon::SUNDAY + $dayOfWeek);
                    $date->setTime(17, 30, 0); // 5PM

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week->start_date->week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek) + 1;
                    $date2->setTime(7, 0, 0); // 7AM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
            // Fishing
            } elseif (in_array($program->id, [3])) {
                foreach([0,1,2,3,4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week->start_date->week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(15, 30, 0);

                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week->start_date->week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(21, 30, 0);
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2 ,]);
                }
            //Afternoon
            } elseif (in_array($program->id, [4,5,7,8])) {
                foreach([0,1,2,3,4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week->start_date->week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(13, 0, 0);
                    
                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week->start_date->week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(17, 30, 1); // 5PM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2 ,]);
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
                foreach([0,1,2,3,4] as $dayOfWeek) { // Mon, Tues, Wed, Thurs, Fri
                    $date = new Carbon; // same as ::now()
                    $date->week = $week->start_date->week;
                    $date->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date->setTime(19, 0, 0);//7PM
                    
                    $date2 = new Carbon; // same as ::now()
                    $date2->week = $week->start_date->week;
                    $date2->day = (Carbon::SATURDAY + $dayOfWeek);
                    $date2->setTime(21, 0, 0); // 9PM
                    array_push($timeSlots, ['start_time' => $date, 'end_time' => $date2]);
                }
            }

            foreach ($timeSlots as $timeSlot) {
                $session = new \App\Models\Session();
                $session->start_time = $timeSlot['start_time'];
                $session->end_time = $timeSlot['end_time'];
                $session->program_id = $program->id;
                $session->week_id = $week->id;
                $session->save();
            }
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.weeks')
            ->with('weeks', Week::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $week = Week::create($request->all());
        $week->save();
        $this->addSessions($week);
        return back()->with('message',
            ["type" => "success", "body" => "Week \"" . $week->name . "\" created successfully."]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function show(Week $week)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function edit(Week $week)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Week $week)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Week  $week
     * @return \Illuminate\Http\Response
     */
    public function destroy(Week $week)
    {
        $week->delete();
        return back()->with('message',
            ["type" => "success", "body" => "Week \"" . $week->name . "\" was deleted."]
        );
    }
}
