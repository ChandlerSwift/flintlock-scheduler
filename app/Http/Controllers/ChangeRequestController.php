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
use Illuminate\Support\Facades\Auth;


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
            ->with('sessions', \App\Models\Session::all())
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
        $cr->session_id = $request['session'];
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

    public function approveRequest(Request $request, ChangeRequest $changeRequest){
        $changeRequest->status = "approved";
        if ($changeRequest->session == null) {
            if ($request['session'] == "")
                return abort(400);
            $changeRequest->session_id = $request['session'];
        }
        $changeRequest->save();

        return back();
    }

    public function waitRequest($id){
        $changeRequest = ChangeRequest::where('id', $id)->first();
        $changeRequest->status = "waitlist";
        $changeRequest->save();

        return back();
    }

    public function confirmRequest($id){
        $changeRequest = ChangeRequest::where('id', $id)->first();
        if (Auth::user()->name != $changeRequest->scout->subcamp) {
            return abort(403);
        }
        $changeRequest->status = "confirmed";
        $changeRequest->save();

        if($changeRequest->action == 'Drop')
            $this->dropRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
        elseif($changeRequest->action == 'Add')
            $this->addRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
        
        return back();
    } 

    public function dropRequest($changeRequest, $scout, $session){
        $session->scouts()->detach($scout->id);
        $scout->refresh(); // Invalidate the cache
        $changeRequest->status = "archived";
        $changeRequest->save();    
    }

    public function addRequest($changeRequest, $scout, $session) {
        $session->scouts()->attach($scout->id);
        $scout->refresh(); // Invalidate the cache
        $changeRequest->status = "archived";
        $changeRequest->save();
    }

}
