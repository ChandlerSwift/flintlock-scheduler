<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipationRequirement;

class ParticipationRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('participation_requirements.index')
            ->with('reqs', ParticipationRequirement::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = new ParticipationRequirement();
        $req->name = $request['name'];
        $req->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function show(ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function edit(ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateParticipationRequirementRequest  $request
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParticipationRequirement $participationRequirement)
    {
        //
    }
}
