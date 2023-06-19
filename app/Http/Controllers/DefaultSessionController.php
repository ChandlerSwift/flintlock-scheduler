<?php

namespace App\Http\Controllers;

use App\Models\DefaultSession;
use App\Models\Program;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DefaultSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sessions')
        ->with('sessions', DefaultSession::all())
        ->with('programs', Program::all());
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
        $session = new DefaultSession;
        $session->program_id = $request->program_id;
        $start_time = explode(':', $request->start_time);
        $end_time = explode(':', $request->end_time);
        $session->start_seconds = 86400 * $request->start_day + 3600 * $start_time[0] + 60 * $start_time[1];
        $session->end_seconds = 86400 * $request->end_day + 3600 * $end_time[0] + 60 * $end_time[1];
        $session->every_day = $request->every_day;
        $session->save();
        return back()->with('message',
            ["type" => "success", "body" => "Session saved successfully."]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DefaultSession  $defaultSession
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultSession $defaultSession)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DefaultSession  $defaultSession
     * @return \Illuminate\Http\Response
     */
    public function edit(DefaultSession $defaultSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DefaultSession  $defaultSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DefaultSession $defaultSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DefaultSession  $defaultSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $defaultSession)
    {
        // I'd like to just say $defaultSession->delete() with a correctly typed
        // parameter, but that doesn't work for some reason -- the
        // DefaultSession passed in is always null??? Anyway, this seems to work
        // :shrug:
        DefaultSession::find($defaultSession)->delete();
        return back()->with('message',
            ["type" => "success", "body" => "Session deleted successfully."]
        );
    }
}
