<?php

namespace App\Http\Controllers;

use App\Models\DefaultSession;
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

    public function choose($id)
    {
        return redirect('/')->cookie('week_id', $id);
    }

    public function addSessions(Week $week) {
        foreach(DefaultSession::all() as $session_prototype) {
            $session = new \App\Models\Session();
            $session->start_time = $week->start_date->addSeconds($session_prototype->start_seconds);
            $session->end_time = $week->start_date->addSeconds($session_prototype->end_seconds);
            $session->program_id = $session_prototype->program_id;
            $session->every_day = $session_prototype->every_day;
            $session->week_id = $week->id;
            $session->save();
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
