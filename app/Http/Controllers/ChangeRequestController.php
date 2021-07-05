<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Providers\RouteServiceProvider;


class ChangeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('requests')
            ->with('troops', DB::table('scouts')->select('unit')->distinct()->get()->pluck('unit'))
            ->with('scouts', \App\Models\Scout::all())
            ->with('programs', \App\Models\Program::all())
            ->with('changeRequests', \App\Models\ChangeRequest::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cr = new ChangeRequest;
        $cr->action = $request['addDrop'];
        $cr->scout_id = $request['Scout'];
        $cr->program_id = $request['program'];
        $cr->notes = $request['notes'];
        $cr->status = "pending";
        
        
        $day = 5;
        $sessionCount = 0;
        $dayProgram = 0;
        if ($request['session'] == 'mon')
            $day = 0; 
        else if ($request['session'] == 'tues')
            $day = 1;
        else if ($request['session'] == 'wed')
            $day = 2;
        else if ($request['session'] == 'thur')
            $day = 3;
        else if ($request['session'] == 'fri')
            $day = 4;

        if ($day < 5 ){
            if ($request['program'] == 1)
                $sessionCount = 1;
            else if ($request['program'] == 2)
                $sessionCount = 5;
            else if ($request['program'] == 3)
                $sessionCount = 9;
            else if ($request['program'] == 4)
                $sessionCount = 13;
            else if ($request['program'] == 5)
                $sessionCount = 18;
            else if ($request['program'] == 6)
                $sessionCount = 23;
            else if ($request['program'] == 7)
                $sessionCount = 28;
            else if ($request['program'] == 8)
                $sessionCount = 33;
            else if ($request['program'] == 9)
                $sessionCount = 38;
            else if ($request['program'] == 10)
                $sessionCount = 43;

            $dayProgram = $sessionCount + $day;
            $cr->session_id = $dayProgram;
        }
        $cr->save();

        $request->session()->flash('status', 'Request submitted!');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function show(ChangeRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ChangeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ChangeRequest  $changerequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChangeRequest $changerequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ChangeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeRequest $request)
    {
        $request->delete();
        return back();
    }


    public function approveRequest($id){
        $changeRequest = ChangeRequest::where('id', $id)->first();
        $changeRequest->status = "approved";
        $changeRequest->save();

        
    }

    public function confirmRequest($id){
        $changeRequest = ChangeRequest::where('id', $id)->first();
        $changeRequest->status = "confirmed";
        $changeRequest->save();
    
        if($changeRequest->action == 'Drop')
            $this->dropRequest($changeRequest);
        elseif($changeRequest->action == 'Add')
            $this->addRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
    } 



    public function dropRequest($changeRequest){
        
        $changeRequest->session->scouts()->detatch($changeRequest->scout->id);
        $changeRequest->status = "archived";
        $changeRequest->save();

            /* if($session->scout->id == $changeRequest->scout->id){
                $session->scouts()->detatch
            } */
            //}
        
        
    }

    public function addRequest($changeRequest, $scout, $session) {

        $session->scouts()->attach($scout->id);
        $scout->refresh(); // Invalidate the cache
        $changeRequest->status = "archived";
        $changeRequest->save();
    }


   
}
