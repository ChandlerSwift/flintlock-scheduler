<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use App\Models\Program;
use App\Models\Scout;
use App\Models\Week;
use Illuminate\Http\Request;
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
        $selected_week = Week::find(request()->cookie('week_id'));

        return view('requests')
            ->with('units', $selected_week->units())
            ->with('scouts', $selected_week->scouts)
            ->with('programs', \App\Models\Program::all()->filter(function ($program, $index) use ($selected_week) {
                return $program->sessions()->where('week_id', $selected_week->id)->where('every_day', false)->count() > 0;
            }))
            ->with('sessions', $selected_week->sessions()->where('every_day', false)->get())
            ->with('changeRequests', $selected_week->changeRequests);
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scout = Scout::find($request['Scout']);
        if ($request['addDrop'] == 'drop' && ! $scout->sessions->pluck('id')->contains($request['session'])) {
            return back()->with('message',
                ['type' => 'danger', 'body' => 'Scout "'.$scout->first_name.' '.$scout->last_name.'" not in that session.']
            );
        } elseif ($request['addDrop'] == 'Swap' && ! $scout->sessions->pluck('program_id')->contains($request['program_id'])) {
            return back()->with('message',
                ['type' => 'danger', 'body' => 'Scout "'.$scout->first_name.' '.$scout->last_name.'" not in another session of '.Program::find($request['program_id'])->name.'.']
            );
        }
        $cr = new ChangeRequest;
        $cr->action = $request['addDrop'];
        $cr->scout_id = $request['Scout'];
        $cr->program_id = $request['program_id'];
        $cr->notes = $request['notes'];
        $cr->status = 'pending';
        $cr->session_id = $request['session'];
        $cr->save();

        $request->session()->flash('status', 'Request submitted!');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ChangeRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ChangeRequest $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChangeRequest $changerequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChangeRequest $request)
    {
        $request->delete();

        return back();
    }

    public function approveRequest(Request $request, ChangeRequest $changeRequest)
    {
        $changeRequest->status = 'approved';
        if ($changeRequest->session == null) {
            if ($request['session'] == '') {
                $request->session()->flash('status', 'You must select a session!');

                return back();
            }
            $changeRequest->notes .= "\n\nFlexible on session";
            $changeRequest->session_id = $request['session'];
        }
        $changeRequest->save();

        return back();
    }

    public function unapproveRequest(Request $request, ChangeRequest $changeRequest)
    {
        $changeRequest->status = 'pending';
        $changeRequest->save();

        return back();
    }

    public function waitRequest($id)
    {
        $changeRequest = ChangeRequest::where('id', $id)->first();
        $changeRequest->status = 'waitlist';
        $changeRequest->save();

        return back();
    }

    public function confirmRequest($id, Week $week)
    {
        $changeRequest = ChangeRequest::where('id', $id)->first();
        if (Auth::user()->name != $changeRequest->scout->subcamp) {
            return abort(403);
        }
        $changeRequest->status = 'confirmed';
        $changeRequest->save();

        if ($changeRequest->action == 'Drop') {
            $this->dropRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
        } elseif ($changeRequest->action == 'Add') {
            $this->addRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
        } elseif ($changeRequest->action == 'Swap') {
            $otherSessions = $week->sessions()->where('program_id', $changeRequest->program_id)->pluck('id');
            $changeRequest->scout->sessions()->detach($otherSessions);
            $this->addRequest($changeRequest, $changeRequest->scout, $changeRequest->session);
        }

        return back();
    }

    public function dropRequest($changeRequest, $scout, $session)
    {
        $session->scouts()->detach($scout->id);
        $scout->refresh(); // Invalidate the cache
        $changeRequest->status = 'archived';
        $changeRequest->save();
    }

    public function addRequest($changeRequest, $scout, $session)
    {
        $session->scouts()->attach($scout->id);
        $scout->refresh(); // Invalidate the cache
        $changeRequest->status = 'archived';
        $changeRequest->save();
    }
}
